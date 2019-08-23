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
use AppBundle\Entity\Feature;
use AppBundle\Form\FeatureType;

/**
 * Feature controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/feature")
 */
class FeatureController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Feature entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="feature_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Feature::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $features = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'features' => $features,
        );
    }

/**
     * Typeahead API endpoint for Feature entities.
     *
     * To make this work, add something like this to FeatureRepository:
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
     * @Route("/typeahead", name="feature_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(Feature::class);
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
     * Search for Feature entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Feature repository. Reregion the fieldName with
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
     * @Route("/search", name="feature_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:Feature');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $features = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $features = array();
	}

        return array(
            'features' => $features,
            'q' => $q,
        );
    }

    /**
     * Creates a new Feature entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="feature_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $feature = new Feature();
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feature);
            $em->flush();

            $this->addFlash('success', 'The new feature was created.');
            return $this->redirectToRoute('feature_show', array('id' => $feature->getId()));
        }

        return array(
            'feature' => $feature,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Feature entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="feature_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Feature entity.
     *
     * @param Feature $feature
     *
     * @return array
     *
     * @Route("/{id}", name="feature_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Feature $feature)
    {

        return array(
            'feature' => $feature,
        );
    }

    /**
     * Displays a form to edit an existing Feature entity.
     *
     *
     * @param Request $request
     * @param Feature $feature
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="feature_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Feature $feature)
    {
        $editForm = $this->createForm(FeatureType::class, $feature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The feature has been updated.');
            return $this->redirectToRoute('feature_show', array('id' => $feature->getId()));
        }

        return array(
            'feature' => $feature,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Feature entity.
     *
     *
     * @param Request $request
     * @param Feature $feature
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="feature_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Feature $feature)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($feature);
        $em->flush();
        $this->addFlash('success', 'The feature was deleted.');

        return $this->redirectToRoute('feature_index');
    }
}
