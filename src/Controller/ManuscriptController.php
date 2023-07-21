<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Manuscript;
use App\Form\ManuscriptContentsType;
use App\Form\ManuscriptContributionsType;
use App\Form\ManuscriptFeaturesType;
use App\Form\ManuscriptFilterType;
use App\Form\ManuscriptType;
use App\Repository\ManuscriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/manuscript')]
class ManuscriptController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'manuscript_index', methods: ['GET'])]
    #[Template]
    public function indexAction(Request $request, ManuscriptRepository $repo) {
        $sort = $request->query->get('sort');
        $qb = $repo->indexQuery();
        $sortedQuery = $repo->getSortedQuery($qb, $sort);
        $manuscripts = $this->paginator->paginate($sortedQuery, $request->query->getint('page', 1), 24);
        $form = $this->createForm(ManuscriptFilterType::class);

        return [
            'manuscripts' => $manuscripts,
            'form' => $form->createView(),
            'data' => $form->getData(),
            'sort' => $sort,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'manuscript_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, ManuscriptRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q)->execute() as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result . ' ' . $result->getCallNumber(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @return array
     */
    #[Route(path: '/search', name: 'manuscript_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, ManuscriptRepository $repo, FilterBuilderUpdaterInterface $filterBuilderUpdater) {
        $q = $request->query->get('q');
        $sort = $request->query->get('sort');
        $untitled = $request->query->get('untitled');
        $qb = $repo->searchQuery($q, $untitled);
        $form = $this->createForm(ManuscriptFilterType::class);
        $active = [];
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->all($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
            $active = $repo->getActiveFilters($form);
        }
        $sortedQuery = $repo->getSortedQuery($qb, $sort);
        $manuscripts = $this->paginator->paginate($sortedQuery, $request->query->getInt('page', 1), 24);

        return [
            'manuscripts' => $manuscripts,
            'active' => $active,
            'q' => $q,
            'sort' => $sort,
            'untitled' => $untitled,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'manuscript_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $manuscript = new Manuscript();
        $form = $this->createForm(ManuscriptType::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($manuscript);
            $entityManager->flush();

            $this->addFlash('success', 'The new manuscript was created.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'manuscript_show', methods: ['GET'])]
    #[Template]
    public function showAction(Manuscript $manuscript) {
        return [
            'manuscript' => $manuscript,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'manuscript_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Manuscript $manuscript) {
        $editForm = $this->createForm(ManuscriptType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ('complete' === $request->request->get('submit', '')) {
                $manuscript->setComplete(true);
            } else {
                $manuscript->setComplete(false);
            }

            $entityManager->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'manuscript_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Manuscript $manuscript) {
        $entityManager->remove($manuscript);
        $entityManager->flush();
        $this->addFlash('success', 'The manuscript was deleted.');

        return $this->redirectToRoute('manuscript_index');
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/contents', name: 'manuscript_contents', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function contentsAction(EntityManagerInterface $entityManager, Request $request, Manuscript $manuscript) {
        $oldContents = $manuscript->getManuscriptContents()->toArray();

        $editForm = $this->createForm(ManuscriptContentsType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $msContents = $manuscript->getManuscriptContents();

            foreach ($oldContents as $content) {
                if ( ! $msContents->contains($content)) {
                    $manuscript->removeManuscriptContent($content);
                    $entityManager->remove($content);
                }
            }

            foreach ($manuscript->getManuscriptContents() as $content) {
                $content->setManuscript($manuscript);
            }
            $entityManager->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/contributions', name: 'manuscript_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function contributionsAction(EntityManagerInterface $entityManager, Request $request, Manuscript $manuscript) {
        $oldContributions = $manuscript->getManuscriptContributions()->toArray();

        $editForm = $this->createForm(ManuscriptContributionsType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $msContributions = $manuscript->getManuscriptContributions();

            foreach ($oldContributions as $contribution) {
                if ( ! $msContributions->contains($contribution)) {
                    $manuscript->removeManuscriptContribution($contribution);
                    $entityManager->remove($contribution);
                }
            }

            foreach ($msContributions as $contribution) {
                $contribution->setManuscript($manuscript);
            }
            $entityManager->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/features', name: 'manuscript_features', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function featuresAction(EntityManagerInterface $entityManager, Request $request, Manuscript $manuscript) {
        $oldFeatures = $manuscript->getManuscriptFeatures()->toArray();

        $editForm = $this->createForm(ManuscriptFeaturesType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $msFeatures = $manuscript->getManuscriptFeatures();

            foreach ($oldFeatures as $feature) {
                if ( ! $msFeatures->contains($feature)) {
                    $manuscript->removeManuscriptFeature($feature);
                    $entityManager->remove($feature);
                }
            }

            foreach ($msFeatures as $feature) {
                $feature->setManuscript($manuscript);
            }
            $entityManager->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }
}
