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
use AppBundle\Entity\Archive;
use AppBundle\Form\ArchiveType;

/**
 * Archive controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/archive")
 */
class ArchiveController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Archive entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="archive_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Archive::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $archives = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'archives' => $archives,
        );
    }

    /**
     * Typeahead API endpoint for Archive entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="archive_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Archive::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id'   => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Archive entities.
     *
     * @param Request $request
     *
     * @Route("/search", name="archive_search", methods={"GET"})
     * @Template()
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Archive');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $archives = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }
        else {
            $archives = array();
        }

        return array(
            'archives' => $archives,
            'q'        => $q,
        );
    }

    /**
     * Creates a new Archive entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="archive_new", methods={"GET","POST"})
     * @Template()
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

            return $this->redirectToRoute('archive_show', array('id' => $archive->getId()));
        }

        return array(
            'archive' => $archive,
            'form'    => $form->createView(),
        );
    }

    /**
     * Creates a new Archive entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="archive_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Archive entity.
     *
     * @param Archive $archive
     *
     * @return array
     *
     * @Route("/{id}", name="archive_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Archive $archive) {

        return array(
            'archive' => $archive,
        );
    }

    /**
     * Displays a form to edit an existing Archive entity.
     *
     *
     * @param Request $request
     * @param Archive $archive
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="archive_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Archive $archive) {
        $editForm = $this->createForm(ArchiveType::class, $archive);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The archive has been updated.');

            return $this->redirectToRoute('archive_show', array('id' => $archive->getId()));
        }

        return array(
            'archive'   => $archive,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Archive entity.
     *
     *
     * @param Request $request
     * @param Archive $archive
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
