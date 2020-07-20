<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentContributionsType;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Content controller.
 *
 * @Route("/content")
 */
class ContentController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Content entities.
     *
     * @return array
     *
     * @Route("/", name="content_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Content::class, 'e')->orderBy('e.firstLine', 'ASC')->addOrderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $contents = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'contents' => $contents,
        ];
    }

    /**
     * Typeahead API endpoint for Content entities.
     *
     * @Route("/typeahead", name="content_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ContentRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Content entities.
     *
     * @Route("/search", name="content_search", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request, ContentRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $contents = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $contents = [];
        }

        return [
            'contents' => $contents,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Content entity.
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

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Content entity in a popup.
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
     * @return array
     *
     * @Route("/{id}", name="content_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Content $content) {
        return [
            'content' => $content,
        ];
    }

    /**
     * Displays a form to edit an existing Content entity.
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

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Content entity.
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

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'edit_form' => $editForm->createView(),
        ];
    }
}
