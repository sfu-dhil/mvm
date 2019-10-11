<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Content;
use AppBundle\Form\ContentContributionsType;
use AppBundle\Form\ContentType;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Content controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/content")
 */
class ContentController extends Controller implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Content entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="content_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Content::class, 'e')->orderBy('e.title', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $contents = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'contents' => $contents,
        );
    }

    /**
     * Typeahead API endpoint for Content entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="content_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse(array());
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Content::class);
        $data = array();
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = array(
                'id' => $result->getId(),
                'text' => (string) $result,
            );
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Content entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="content_search", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Content');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $contents = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $contents = array();
        }

        return array(
            'contents' => $contents,
            'q' => $q,
        );
    }

    /**
     * Creates a new Content entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="content_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($content);
            $em->flush();

            $this->addFlash('success', 'The new content was created.');

            return $this->redirectToRoute('content_show', array('id' => $content->getId()));
        }

        return array(
            'content' => $content,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Content entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="content_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Content entity.
     *
     * @param Content $content
     *
     * @return array
     *
     * @Route("/{id}", name="content_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Content $content) {
        return array(
            'content' => $content,
        );
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     * @param Request $request
     * @param Content $content
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="content_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Content $content) {
        $editForm = $this->createForm(ContentType::class, $content);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The content has been updated.');

            return $this->redirectToRoute('content_show', array('id' => $content->getId()));
        }

        return array(
            'content' => $content,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Content entity.
     *
     * @param Request $request
     * @param Content $content
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="content_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Content $content) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($content);
        $em->flush();
        $this->addFlash('success', 'The content was deleted.');

        return $this->redirectToRoute('content_index');
    }

    /**
     * Edit the contributions to a piece of content.
     *
     * @param Request $request
     * @param Content $content
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/contributions", name="content_contributions", methods={"GET", "POST"})
     * @Template()
     */
    public function contributionsAction(Request $request, Content $content) {
        $editForm = $this->createForm(ContentContributionsType::class, $content);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            foreach ($content->getContributions() as $contribution) {
                $contribution->setContent($content);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The content has been updated.');

            return $this->redirectToRoute('content_show', array('id' => $content->getId()));
        }

        return array(
            'content' => $content,
            'edit_form' => $editForm->createView(),
        );
    }
}
