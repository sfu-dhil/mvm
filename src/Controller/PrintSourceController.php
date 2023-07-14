<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrintSource;
use App\Form\PrintSourceType;
use App\Repository\PrintSourceRepository;
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

#[Route(path: '/print_source')]
class PrintSourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'print_source_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(PrintSource::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $printSources = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'printSources' => $printSources,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'print_source_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, PrintSourceRepository $repo) {
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
    #[Route(path: '/search', name: 'print_source_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, PrintSourceRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $printSources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $printSources = [];
        }

        return [
            'printSources' => $printSources,
            'q' => $q,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'print_source_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $printSource = new PrintSource();
        $form = $this->createForm(PrintSourceType::class, $printSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($printSource);
            $entityManager->flush();

            $this->addFlash('success', 'The new printSource was created.');

            return $this->redirectToRoute('print_source_show', ['id' => $printSource->getId()]);
        }

        return [
            'printSource' => $printSource,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'print_source_show', methods: ['GET'])]
    #[Template]
    public function showAction(PrintSource $printSource) {
        return [
            'printSource' => $printSource,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'print_source_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(PrintSource $printSource) {
        return [
            'printSource' => $printSource,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'print_source_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, PrintSource $printSource) {
        $editForm = $this->createForm(PrintSourceType::class, $printSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The printSource has been updated.');

            return $this->redirectToRoute('print_source_show', ['id' => $printSource->getId()]);
        }

        return [
            'printSource' => $printSource,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'print_source_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, PrintSource $printSource) {
        $entityManager->remove($printSource);
        $entityManager->flush();
        $this->addFlash('success', 'The printSource was deleted.');

        return $this->redirectToRoute('print_source_index');
    }
}
