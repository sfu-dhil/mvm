<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Region;
use AppBundle\Form\RegionType;

/**
 * Region controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/region")
 */
class RegionController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Region entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="region_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Region::class, 'e')->orderBy('e.name', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $regions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'regions' => $regions,
        );
    }

/**
     * Typeahead API endpoint for Region entities.
     *
     * To make this work, add something like this to RegionRepository:
        //    public function typeaheadQuery($q) {
        //        $qb = $this->createQueryBuilder('e');
        //        $qb->andWhere("e.name LIKE :q");
        //        $qb->orderBy('e.name');
        //        $qb->setParameter('q', "{$q}%");
        //        return $qb->getQuery()->execute();
        //    }
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="region_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(Region::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result,
            ];
        }
        return new JsonResponse($data);
    }
    /**
     * Search for Region entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Region repository. Reregion the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @param Request $request
     *
     * @Route("/search", name="region_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:Region');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $regions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $regions = array();
	}

        return array(
            'regions' => $regions,
            'q' => $q,
        );
    }

    /**
     * Creates a new Region entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="region_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            $this->addFlash('success', 'The new region was created.');
            return $this->redirectToRoute('region_show', array('id' => $region->getId()));
        }

        return array(
            'region' => $region,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Region entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="region_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Region entity.
     *
     * @param Region $region
     *
     * @return array
     *
     * @Route("/{id}", name="region_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Region $region)
    {

        return array(
            'region' => $region,
        );
    }

    /**
     * Displays a form to edit an existing Region entity.
     *
     *
     * @param Request $request
     * @param Region $region
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="region_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Region $region)
    {
        $editForm = $this->createForm(RegionType::class, $region);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The region has been updated.');
            return $this->redirectToRoute('region_show', array('id' => $region->getId()));
        }

        return array(
            'region' => $region,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Region entity.
     *
     *
     * @param Request $request
     * @param Region $region
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="region_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Region $region)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($region);
        $em->flush();
        $this->addFlash('success', 'The region was deleted.');

        return $this->redirectToRoute('region_index');
    }
}
