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
use AppBundle\Entity\ManuscriptFeature;
use AppBundle\Form\ManuscriptFeatureType;

/**
 * ManuscriptFeature controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_feature")
 */
class ManuscriptFeatureController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ManuscriptFeature entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_feature_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptFeature::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptFeatures = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptFeatures' => $manuscriptFeatures,
        );
    }

/**
     * Typeahead API endpoint for ManuscriptFeature entities.
     *
     * To make this work, add something like this to ManuscriptFeatureRepository:
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
     * @Route("/typeahead", name="manuscript_feature_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ManuscriptFeature::class);
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
     * Search for ManuscriptFeature entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ManuscriptFeature repository. Reregion the fieldName with
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
     * @Route("/search", name="manuscript_feature_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ManuscriptFeature');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $manuscriptFeatures = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $manuscriptFeatures = array();
	}

        return array(
            'manuscriptFeatures' => $manuscriptFeatures,
            'q' => $q,
        );
    }

    /**
     * Creates a new ManuscriptFeature entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_feature_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $manuscriptFeature = new ManuscriptFeature();
        $form = $this->createForm(ManuscriptFeatureType::class, $manuscriptFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manuscriptFeature);
            $em->flush();

            $this->addFlash('success', 'The new manuscriptFeature was created.');
            return $this->redirectToRoute('manuscript_feature_show', array('id' => $manuscriptFeature->getId()));
        }

        return array(
            'manuscriptFeature' => $manuscriptFeature,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ManuscriptFeature entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_feature_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ManuscriptFeature entity.
     *
     * @param ManuscriptFeature $manuscriptFeature
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_feature_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptFeature $manuscriptFeature)
    {

        return array(
            'manuscriptFeature' => $manuscriptFeature,
        );
    }

    /**
     * Displays a form to edit an existing ManuscriptFeature entity.
     *
     *
     * @param Request $request
     * @param ManuscriptFeature $manuscriptFeature
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_feature_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ManuscriptFeature $manuscriptFeature)
    {
        $editForm = $this->createForm(ManuscriptFeatureType::class, $manuscriptFeature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscriptFeature has been updated.');
            return $this->redirectToRoute('manuscript_feature_show', array('id' => $manuscriptFeature->getId()));
        }

        return array(
            'manuscriptFeature' => $manuscriptFeature,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ManuscriptFeature entity.
     *
     *
     * @param Request $request
     * @param ManuscriptFeature $manuscriptFeature
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="manuscript_feature_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ManuscriptFeature $manuscriptFeature)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manuscriptFeature);
        $em->flush();
        $this->addFlash('success', 'The manuscriptFeature was deleted.');

        return $this->redirectToRoute('manuscript_feature_index');
    }
}
