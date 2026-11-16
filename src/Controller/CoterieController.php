<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Coterie;
use App\Form\CoterieType;
use App\Repository\CoterieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/coterie')]
class CoterieController extends AbstractController {
    #[Route(path: '/', name: 'coterie_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, CoterieRepository $coterieRepository) : array {
        $query = $coterieRepository->indexQuery();
        $coteries = $query->execute();

        // $qb = $this->createQueryBuilder('coterie');
        // $qb->leftJoin('coterie.regions', 'region');
        // $qb->where('MATCH(coterie.label, coterie.description) AGAINST (:q BOOLEAN) > 0.0');
        // $qb->orWhere('MATCH(region.name) AGAINST (:q BOOLEAN) > 0.0');
        // $qb->setParameter('q', $q);

        // return $qb->getQuery();

        return [
            'coteries' => $coteries,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'coterie_new', methods: ['GET', 'POST'])]
    #[Template]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request) {
        $coterie = new Coterie();
        $form = $this->createForm(CoterieType::class, $coterie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coterie);
            $entityManager->flush();
            $this->addFlash('success', 'The new coterie has been saved.');

            return $this->redirectToRoute('coterie_show', ['id' => $coterie->getId()]);
        }

        return [
            'coterie' => $coterie,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'coterie_show', methods: ['GET'])]
    #[Template]
    public function show(Coterie $coterie) {
        return [
            'coterie' => $coterie,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'coterie_modal', methods: ['GET'])]
    #[Template]
    public function modal(Coterie $coterie) {
        return [
            'coterie' => $coterie,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'coterie_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(EntityManagerInterface $entityManager, Request $request, Coterie $coterie) {
        $form = $this->createForm(CoterieType::class, $coterie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The updated coterie has been saved.');

            return $this->redirectToRoute('coterie_show', ['id' => $coterie->getId()]);
        }

        return [
            'coterie' => $coterie,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'coterie_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, Request $request, Coterie $coterie) {
        $entityManager->remove($coterie);
        $entityManager->flush();
        $this->addFlash('success', 'The coterie has been deleted.');

        return $this->redirectToRoute('coterie_index');
    }
}
