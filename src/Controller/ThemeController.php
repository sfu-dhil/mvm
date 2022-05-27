<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
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
 * Theme controller.
 *
 * @Route("/theme")
 */
class ThemeController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Theme entities.
     *
     * @return array
     *
     * @Route("/", name="theme_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Theme::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $themes = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'themes' => $themes,
        ];
    }

    /**
     * Typeahead API endpoint for Theme entities.
     *
     * To make this work, add something like this to ThemeRepository:
     *
     * @Route("/typeahead", name="theme_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ThemeRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q)->execute() as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/search", name="theme_search", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, ThemeRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $themes = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $themes = [];
        }

        return [
            'themes' => $themes,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Theme entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="theme_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            $this->addFlash('success', 'The new theme was created.');

            return $this->redirectToRoute('theme_show', ['id' => $theme->getId()]);
        }

        return [
            'theme' => $theme,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Theme entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="theme_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @return array
     *
     * @Route("/{id}", name="theme_show", methods={"GET"})
     * @Template
     */
    public function showAction(Theme $theme) {
        return [
            'theme' => $theme,
        ];
    }

    /**
     * Finds and displays a Theme modal.
     *
     * @return array
     *
     * @Route("/{id}/modal", name="theme_modal", methods={"GET"})
     * @Template
     */
    public function modalAction(Theme $theme) {
        return [
            'theme' => $theme,
        ];
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="theme_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Theme $theme) {
        $editForm = $this->createForm(ThemeType::class, $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The theme has been updated.');

            return $this->redirectToRoute('theme_show', ['id' => $theme->getId()]);
        }

        return [
            'theme' => $theme,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Theme entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="theme_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Theme $theme) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($theme);
        $em->flush();
        $this->addFlash('success', 'The theme was deleted.');

        return $this->redirectToRoute('theme_index');
    }
}
