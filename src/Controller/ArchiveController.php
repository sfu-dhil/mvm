<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Archive;
use App\Form\ArchiveType;
use App\Repository\ArchiveRepository;
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

#[Route(path: '/archive')]
class ArchiveController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'archive_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) : array {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Archive::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $archives = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'archives' => $archives,
        ];
    }

    #[Route(path: '/typeahead', name: 'archive_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, ArchiveRepository $repo) : JsonResponse {
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

    #[Route(path: '/search', name: 'archive_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, ArchiveRepository $repo) : array {
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

    #[Route(path: '/new', name: 'archive_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) : array|RedirectResponse {
        $archive = new Archive();
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($archive);
            $entityManager->flush();

            $this->addFlash('success', 'The new archive was created.');

            return $this->redirectToRoute('archive_show', ['id' => $archive->getId()]);
        }

        return [
            'archive' => $archive,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'archive_show', methods: ['GET'])]
    #[Template]
    public function showAction(Archive $archive) : array {
        return [
            'archive' => $archive,
        ];
    }

    #[Route(path: '/{id}/modal', name: 'archive_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(Archive $archive) : array {
        return [
            'archive' => $archive,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'archive_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Archive $archive) : array|RedirectResponse {
        $editForm = $this->createForm(ArchiveType::class, $archive);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The archive has been updated.');

            return $this->redirectToRoute('archive_show', ['id' => $archive->getId()]);
        }

        return [
            'archive' => $archive,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'archive_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Archive $archive) : array|RedirectResponse {
        $entityManager->remove($archive);
        $entityManager->flush();
        $this->addFlash('success', 'The archive was deleted.');

        return $this->redirectToRoute('archive_index');
    }
}
