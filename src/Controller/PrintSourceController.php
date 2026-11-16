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
class PrintSourceController extends AbstractController {
    /**
     * @return array
     */
    #[Route(path: '/', name: 'print_source_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(PrintSource::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $printSources = $query->execute();

        // $qb->where('MATCH(print_source.label, print_source.description) AGAINST (:q BOOLEAN) > 0.0');
        // $qb->orWhere('MATCH(region.name) AGAINST (:q BOOLEAN) > 0.0');
        return [
            'printSources' => $printSources,
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
