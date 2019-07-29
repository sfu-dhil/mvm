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
use AppBundle\Entity\ManuscriptContribution;
use AppBundle\Form\ManuscriptContributionType;

/**
 * ManuscriptContribution controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_contribution")
 */
class ManuscriptContributionController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ManuscriptContribution entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_contribution_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptContributions' => $manuscriptContributions,
        );
    }

/**
     * Typeahead API endpoint for ManuscriptContribution entities.
     *
     * To make this work, add something like this to ManuscriptContributionRepository:
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
     * @Route("/typeahead", name="manuscript_contribution_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ManuscriptContribution::class);
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
     * Search for ManuscriptContribution entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ManuscriptContribution repository. Replace the fieldName with
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
     * @Route("/search", name="manuscript_contribution_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ManuscriptContribution');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $manuscriptContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $manuscriptContributions = array();
	}

        return array(
            'manuscriptContributions' => $manuscriptContributions,
            'q' => $q,
        );
    }

    /**
     * Creates a new ManuscriptContribution entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_contribution_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $manuscriptContribution = new ManuscriptContribution();
        $form = $this->createForm(ManuscriptContributionType::class, $manuscriptContribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manuscriptContribution);
            $em->flush();

            $this->addFlash('success', 'The new manuscriptContribution was created.');
            return $this->redirectToRoute('manuscript_contribution_show', array('id' => $manuscriptContribution->getId()));
        }

        return array(
            'manuscriptContribution' => $manuscriptContribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ManuscriptContribution entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_contribution_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ManuscriptContribution entity.
     *
     * @param ManuscriptContribution $manuscriptContribution
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_contribution_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptContribution $manuscriptContribution)
    {

        return array(
            'manuscriptContribution' => $manuscriptContribution,
        );
    }

    /**
     * Displays a form to edit an existing ManuscriptContribution entity.
     *
     *
     * @param Request $request
     * @param ManuscriptContribution $manuscriptContribution
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_contribution_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ManuscriptContribution $manuscriptContribution)
    {
        $editForm = $this->createForm(ManuscriptContributionType::class, $manuscriptContribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscriptContribution has been updated.');
            return $this->redirectToRoute('manuscript_contribution_show', array('id' => $manuscriptContribution->getId()));
        }

        return array(
            'manuscriptContribution' => $manuscriptContribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ManuscriptContribution entity.
     *
     *
     * @param Request $request
     * @param ManuscriptContribution $manuscriptContribution
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="manuscript_contribution_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ManuscriptContribution $manuscriptContribution)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manuscriptContribution);
        $em->flush();
        $this->addFlash('success', 'The manuscriptContribution was deleted.');

        return $this->redirectToRoute('manuscript_contribution_index');
    }
}
