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
use AppBundle\Entity\ContentContribution;
use AppBundle\Form\ContentContributionType;

/**
 * ContentContribution controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/content_contribution")
 */
class ContentContributionController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ContentContribution entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="content_contribution_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ContentContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $contentContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'contentContributions' => $contentContributions,
        );
    }

/**
     * Typeahead API endpoint for ContentContribution entities.
     *
     * To make this work, add something like this to ContentContributionRepository:
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
     * @Route("/typeahead", name="content_contribution_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ContentContribution::class);
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
     * Search for ContentContribution entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ContentContribution repository. Replace the fieldName with
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
     * @Route("/search", name="content_contribution_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ContentContribution');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $contentContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $contentContributions = array();
	}

        return array(
            'contentContributions' => $contentContributions,
            'q' => $q,
        );
    }

    /**
     * Creates a new ContentContribution entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="content_contribution_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $contentContribution = new ContentContribution();
        $form = $this->createForm(ContentContributionType::class, $contentContribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentContribution);
            $em->flush();

            $this->addFlash('success', 'The new contentContribution was created.');
            return $this->redirectToRoute('content_contribution_show', array('id' => $contentContribution->getId()));
        }

        return array(
            'contentContribution' => $contentContribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ContentContribution entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="content_contribution_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ContentContribution entity.
     *
     * @param ContentContribution $contentContribution
     *
     * @return array
     *
     * @Route("/{id}", name="content_contribution_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ContentContribution $contentContribution)
    {

        return array(
            'contentContribution' => $contentContribution,
        );
    }

    /**
     * Displays a form to edit an existing ContentContribution entity.
     *
     *
     * @param Request $request
     * @param ContentContribution $contentContribution
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="content_contribution_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ContentContribution $contentContribution)
    {
        $editForm = $this->createForm(ContentContributionType::class, $contentContribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contentContribution has been updated.');
            return $this->redirectToRoute('content_contribution_show', array('id' => $contentContribution->getId()));
        }

        return array(
            'contentContribution' => $contentContribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ContentContribution entity.
     *
     *
     * @param Request $request
     * @param ContentContribution $contentContribution
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="content_contribution_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ContentContribution $contentContribution)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contentContribution);
        $em->flush();
        $this->addFlash('success', 'The contentContribution was deleted.');

        return $this->redirectToRoute('content_contribution_index');
    }
}
