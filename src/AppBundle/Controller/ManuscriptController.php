<?php

namespace AppBundle\Controller;

use AppBundle\Form\ManuscriptContributionsType;
use AppBundle\Form\ManuscriptFeaturesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Manuscript;
use AppBundle\Form\ManuscriptType;

/**
 * Manuscript controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript")
 */
class ManuscriptController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Manuscript entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Manuscript::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscripts = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscripts' => $manuscripts,
        );
    }

/**
     * Typeahead API endpoint for Manuscript entities.
     *
     * To make this work, add something like this to ManuscriptRepository:
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
     * @Route("/typeahead", name="manuscript_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(Manuscript::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result . ' ' . $result->getCallNumber(),
            ];
        }
        return new JsonResponse($data);
    }
    /**
     * Search for Manuscript entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Manuscript repository. Reregion the fieldName with
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
     * @Route("/search", name="manuscript_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:Manuscript');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $manuscripts = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $manuscripts = array();
	}

        return array(
            'manuscripts' => $manuscripts,
            'q' => $q,
        );
    }

    /**
     * Creates a new Manuscript entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $manuscript = new Manuscript();
        $form = $this->createForm(ManuscriptType::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manuscript);
            $em->flush();

            $this->addFlash('success', 'The new manuscript was created.');
            return $this->redirectToRoute('manuscript_show', array('id' => $manuscript->getId()));
        }

        return array(
            'manuscript' => $manuscript,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Manuscript entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Manuscript entity.
     *
     * @param Manuscript $manuscript
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Manuscript $manuscript)
    {

        return array(
            'manuscript' => $manuscript,
        );
    }

    /**
     * Displays a form to edit an existing Manuscript entity.
     *
     *
     * @param Request $request
     * @param Manuscript $manuscript
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Manuscript $manuscript)
    {
        $editForm = $this->createForm(ManuscriptType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if($request->request->get('submit', '') === 'complete') {
                $manuscript->setComplete(true);
            } else {
                $manuscript->setComplete(false);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');
            return $this->redirectToRoute('manuscript_show', array('id' => $manuscript->getId()));
        }

        return array(
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Manuscript entity.
     *
     *
     * @param Request $request
     * @param Manuscript $manuscript
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="manuscript_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Manuscript $manuscript)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manuscript);
        $em->flush();
        $this->addFlash('success', 'The manuscript was deleted.');

        return $this->redirectToRoute('manuscript_index');
    }

    /**
     * Edits a Manuscript's content entities
     *
     * @param Request $request
     * @param Manuscript $manuscript
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/contents", name="manuscript_contents", methods={"GET"})
     * @Template()
     */
    public function contentAction(Request $request, Manuscript $manuscript) {

    }

    /**
     * Edits a Manuscript's contributions entities
     *
     * @param Request $request
     * @param Manuscript $manuscript
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/contributions", name="manuscript_contributions", methods={"GET", "POST"})
     * @Template()
     */
    public function contributionsAction(Request $request, Manuscript $manuscript) {
        $editForm = $this->createForm(ManuscriptContributionsType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            foreach($manuscript->getManuscriptContributions() as $contribution) {
                $contribution->setManuscript($manuscript);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');
            return $this->redirectToRoute('manuscript_show', array('id' => $manuscript->getId()));
        }
        return array(
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Edits a Manuscript's feature entities
     *
     * @param Request $request
     * @param Manuscript $manuscript
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/features", name="manuscript_features", methods={"GET", "POST"})
     * @Template()
     */
    public function featuresAction(Request $request, Manuscript $manuscript) {
        $editForm = $this->createForm(ManuscriptFeaturesType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            foreach($manuscript->getManuscriptFeatures() as $feature) {
                $feature->setManuscript($manuscript);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');
            return $this->redirectToRoute('manuscript_show', array('id' => $manuscript->getId()));
        }
        return array(
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        );
    }

}
