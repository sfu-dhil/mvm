<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/theme')]
class ThemeController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'theme_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Theme::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $themes = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'themes' => $themes,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'theme_typeahead', methods: ['GET'])]
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
     * @return array
     */
    #[Route(path: '/search', name: 'theme_search', methods: ['GET'])]
    #[Template]
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
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'theme_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($theme);
            $entityManager->flush();

            $this->addFlash('success', 'The new theme was created.');

            return $this->redirectToRoute('theme_show', ['id' => $theme->getId()]);
        }

        return [
            'theme' => $theme,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'theme_show', methods: ['GET'])]
    #[Template]
    public function showAction(Theme $theme) {
        return [
            'theme' => $theme,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'theme_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(Theme $theme) {
        return [
            'theme' => $theme,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'theme_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Theme $theme) {
        $editForm = $this->createForm(ThemeType::class, $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The theme has been updated.');

            return $this->redirectToRoute('theme_show', ['id' => $theme->getId()]);
        }

        return [
            'theme' => $theme,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'theme_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Theme $theme) {
        $entityManager->remove($theme);
        $entityManager->flush();
        $this->addFlash('success', 'The theme was deleted.');

        return $this->redirectToRoute('theme_index');
    }
}
