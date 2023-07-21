<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Coterie;
use App\Form\CoterieType;
use App\Repository\CoterieRepository;
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

#[Route(path: '/coterie')]
class CoterieController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'coterie_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, CoterieRepository $coterieRepository) : array {
        $query = $coterieRepository->indexQuery();
        $coteries = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'coteries' => $coteries,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/search', name: 'coterie_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, CoterieRepository $coterieRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $coterieRepository->searchQuery($q);
            $coteries = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);
        } else {
            $coteries = [];
        }

        return [
            'coteries' => $coteries,
            'q' => $q,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'coterie_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, CoterieRepository $coterieRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($coterieRepository->typeaheadQuery($q)->execute() as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
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
