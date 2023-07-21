<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Feature;
use App\Form\FeatureType;
use App\Repository\FeatureRepository;
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

#[Route(path: '/feature')]
class FeatureController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'feature_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Feature::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $features = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'features' => $features,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'feature_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, FeatureRepository $repo) {
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
    #[Route(path: '/search', name: 'feature_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, FeatureRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $features = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $features = [];
        }

        return [
            'features' => $features,
            'q' => $q,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'feature_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $feature = new Feature();
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($feature);
            $entityManager->flush();

            $this->addFlash('success', 'The new feature was created.');

            return $this->redirectToRoute('feature_show', ['id' => $feature->getId()]);
        }

        return [
            'feature' => $feature,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'feature_show', methods: ['GET'])]
    #[Template]
    public function showAction(Feature $feature) {
        return [
            'feature' => $feature,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'feature_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Feature $feature) {
        $editForm = $this->createForm(FeatureType::class, $feature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The feature has been updated.');

            return $this->redirectToRoute('feature_show', ['id' => $feature->getId()]);
        }

        return [
            'feature' => $feature,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'feature_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Feature $feature) {
        $entityManager->remove($feature);
        $entityManager->flush();
        $this->addFlash('success', 'The feature was deleted.');

        return $this->redirectToRoute('feature_index');
    }
}
