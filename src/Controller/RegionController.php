<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
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

#[Route(path: '/region')]
class RegionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'region_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Region::class, 'e')->orderBy('e.name', 'ASC');
        $query = $qb->getQuery();

        $regions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'regions' => $regions,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'region_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, RegionRepository $repo) {
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
    #[Route(path: '/search', name: 'region_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, RegionRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $regions = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $regions = [];
        }

        return [
            'regions' => $regions,
            'q' => $q,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'region_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($region);
            $entityManager->flush();

            $this->addFlash('success', 'The new region was created.');

            return $this->redirectToRoute('region_show', ['id' => $region->getId()]);
        }

        return [
            'region' => $region,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'region_show', methods: ['GET'])]
    #[Template]
    public function showAction(Region $region) {
        return [
            'region' => $region,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'region_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(Region $region) {
        return [
            'region' => $region,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'region_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Region $region) {
        $editForm = $this->createForm(RegionType::class, $region);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The region has been updated.');

            return $this->redirectToRoute('region_show', ['id' => $region->getId()]);
        }

        return [
            'region' => $region,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'region_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Region $region) {
        $entityManager->remove($region);
        $entityManager->flush();
        $this->addFlash('success', 'The region was deleted.');

        return $this->redirectToRoute('region_index');
    }
}
