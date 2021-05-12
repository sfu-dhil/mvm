<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Feature;
use App\Form\FeatureType;
use App\Repository\FeatureRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Feature controller.
 *
 * @Route("/feature")
 */
class FeatureController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Feature entities.
     *
     * @return array
     *
     * @Route("/", name="feature_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Feature::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $features = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'features' => $features,
        ];
    }

    /**
     * Typeahead API endpoint for Feature entities.
     *
     * @Route("/typeahead", name="feature_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, FeatureRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Feature entities.
     *
     * To make this work, add a method like this one to the
     * App:Feature repository. Reregion the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @Route("/search", name="feature_search", methods={"GET"})
     * @Template
     *
     * @return array
     */
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
     * Creates a new Feature entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="feature_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $feature = new Feature();
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feature);
            $em->flush();

            $this->addFlash('success', 'The new feature was created.');

            return $this->redirectToRoute('feature_show', ['id' => $feature->getId()]);
        }

        return [
            'feature' => $feature,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Feature entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="feature_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Feature entity.
     *
     * @return array
     *
     * @Route("/{id}", name="feature_show", methods={"GET"})
     * @Template
     */
    public function showAction(Feature $feature) {
        return [
            'feature' => $feature,
        ];
    }

    /**
     * Displays a form to edit an existing Feature entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="feature_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Feature $feature) {
        $editForm = $this->createForm(FeatureType::class, $feature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The feature has been updated.');

            return $this->redirectToRoute('feature_show', ['id' => $feature->getId()]);
        }

        return [
            'feature' => $feature,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Feature entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="feature_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Feature $feature) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($feature);
        $em->flush();
        $this->addFlash('success', 'The feature was deleted.');

        return $this->redirectToRoute('feature_index');
    }
}
