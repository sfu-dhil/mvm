<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Archive;
use App\Form\ArchiveType;
use App\Repository\ArchiveRepository;
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
 * Archive controller.
 *
 * @Route("/archive")
 */
class ArchiveController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Archive entities.
     *
     * @return array
     *
     * @Route("/", name="archive_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Archive::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $archives = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'archives' => $archives,
        ];
    }

    /**
     * Typeahead API endpoint for Archive entities.
     *
     * @Route("/typeahead", name="archive_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ArchiveRepository $repo) {
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
     * Search for Archive entities.
     *
     * @Route("/search", name="archive_search", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, ArchiveRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $archives = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $archives = [];
        }

        return [
            'archives' => $archives,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Archive entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="archive_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $archive = new Archive();
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($archive);
            $em->flush();

            $this->addFlash('success', 'The new archive was created.');

            return $this->redirectToRoute('archive_show', ['id' => $archive->getId()]);
        }

        return [
            'archive' => $archive,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Archive entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="archive_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Archive entity.
     *
     * @return array
     *
     * @Route("/{id}", name="archive_show", methods={"GET"})
     * @Template
     */
    public function showAction(Archive $archive) {
        return [
            'archive' => $archive,
        ];
    }

    /**
     * Finds and displays a Archive modal.
     *
     * @return array
     *
     * @Route("/{id}/modal", name="archive_modal", methods={"GET"})
     * @Template
     */
    public function modalAction(Archive $archive) {
        return [
            'archive' => $archive,
        ];
    }

    /**
     * Displays a form to edit an existing Archive entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="archive_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Archive $archive) {
        $editForm = $this->createForm(ArchiveType::class, $archive);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The archive has been updated.');

            return $this->redirectToRoute('archive_show', ['id' => $archive->getId()]);
        }

        return [
            'archive' => $archive,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Archive entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="archive_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Archive $archive) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($archive);
        $em->flush();
        $this->addFlash('success', 'The archive was deleted.');

        return $this->redirectToRoute('archive_index');
    }
}
