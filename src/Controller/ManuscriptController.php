<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Manuscript;
use App\Form\ManuscriptContentsType;
use App\Form\ManuscriptContributionsType;
use App\Form\ManuscriptFeaturesType;
use App\Form\ManuscriptType;
use App\Form\ManuscriptFilterType;
use App\Repository\ManuscriptRepository;
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

/**
 * Manuscript controller.
 *
 * @Route("/manuscript")
 */
class ManuscriptController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Manuscript entities.
     *
     * @return array
     *
     * @Route("/", name="manuscript_index", methods={"GET"})
     * @Template
     */
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
            'sort' => $sort
        ];
    }

    /**
     * Typeahead API endpoint for Manuscript entities.
     *
     * @Route("/typeahead", name="manuscript_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
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
     * @Route("/search", name="manuscript_search", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, ManuscriptRepository $repo, FilterBuilderUpdaterInterface $filterBuilderUpdater) {
        $q = $request->query->get('q');
        $sort = $request->query->get('sort');
        $qb = $repo->indexQuery();
        if ( $q ){
            $qb = $repo->searchQuery($q);
        }
        $form = $this->createForm(ManuscriptFilterType::class);
        $active = [];
        if ($request->query->has($form->getName())){
            $form->submit($request->query->get($form->getName()));
            $filterBuilderUpdater->addFilterConditions($form, $qb);
            $active = $repo->getActiveFilters($form);
        };
        $sortedQuery = $repo->getSortedQuery($qb, $sort);
        $manuscripts = $this->paginator->paginate($sortedQuery, $request->query->getInt('page', 1), 24);

        return [
            'manuscripts' => $manuscripts,
            'active' => $active,
            'q' => $q,
            'sort' => $sort,
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new Manuscript entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $manuscript = new Manuscript();
        $form = $this->createForm(ManuscriptType::class, $manuscript);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manuscript);
            $em->flush();

            $this->addFlash('success', 'The new manuscript was created.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Manuscript entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Manuscript entity.
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_show", methods={"GET"})
     * @Template
     */
    public function showAction(Manuscript $manuscript) {
        return [
            'manuscript' => $manuscript,
        ];
    }

    /**
     * Displays a form to edit an existing Manuscript entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Manuscript $manuscript) {
        $editForm = $this->createForm(ManuscriptType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ('complete' === $request->request->get('submit', '')) {
                $manuscript->setComplete(true);
            } else {
                $manuscript->setComplete(false);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Manuscript entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="manuscript_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Manuscript $manuscript) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manuscript);
        $em->flush();
        $this->addFlash('success', 'The manuscript was deleted.');

        return $this->redirectToRoute('manuscript_index');
    }

    /**
     * Edits a Manuscript's content entities.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/contents", name="manuscript_contents", methods={"GET", "POST"})
     * @Template
     */
    public function contentsAction(Request $request, Manuscript $manuscript) {
        $oldContents = $manuscript->getManuscriptContents()->toArray();

        $editForm = $this->createForm(ManuscriptContentsType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $msContents = $manuscript->getManuscriptContents();

            foreach ($oldContents as $content) {
                if ( ! $msContents->contains($content)) {
                    $manuscript->removeManuscriptContent($content);
                    $em->remove($content);
                }
            }

            foreach ($manuscript->getManuscriptContents() as $content) {
                $content->setManuscript($manuscript);
            }
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits a Manuscript's contributions entities.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/contributions", name="manuscript_contributions", methods={"GET", "POST"})
     * @Template
     */
    public function contributionsAction(Request $request, Manuscript $manuscript) {
        $oldContributions = $manuscript->getManuscriptContributions()->toArray();

        $editForm = $this->createForm(ManuscriptContributionsType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $msContributions = $manuscript->getManuscriptContributions();

            foreach ($oldContributions as $contribution) {
                if ( ! $msContributions->contains($contribution)) {
                    $manuscript->removeManuscriptContribution($contribution);
                    $em->remove($contribution);
                }
            }

            foreach ($msContributions as $contribution) {
                $contribution->setManuscript($manuscript);
            }
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits a Manuscript's feature entities.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/features", name="manuscript_features", methods={"GET", "POST"})
     * @Template
     */
    public function featuresAction(Request $request, Manuscript $manuscript) {
        $oldFeatures = $manuscript->getManuscriptFeatures()->toArray();

        $editForm = $this->createForm(ManuscriptFeaturesType::class, $manuscript);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $msFeatures = $manuscript->getManuscriptFeatures();

            foreach ($oldFeatures as $feature) {
                if ( ! $msFeatures->contains($feature)) {
                    $manuscript->removeManuscriptFeature($feature);
                    $em->remove($feature);
                }
            }

            foreach ($msFeatures as $feature) {
                $feature->setManuscript($manuscript);
            }
            $em->flush();
            $this->addFlash('success', 'The manuscript has been updated.');

            return $this->redirectToRoute('manuscript_show', ['id' => $manuscript->getId()]);
        }

        return [
            'manuscript' => $manuscript,
            'edit_form' => $editForm->createView(),
        ];
    }
}
