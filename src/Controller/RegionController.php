<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
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
 * Region controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/region")
 */
class RegionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Region entities.
     *
     * @return array
     *
     * @Route("/", name="region_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Region::class, 'e')->orderBy('e.name', 'ASC');
        $query = $qb->getQuery();

        $regions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'regions' => $regions,
        ];
    }

    /**
     * Typeahead API endpoint for Region entities.
     *
     * @Route("/typeahead", name="region_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, RegionRepository $repo) {
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
     * Search for Region entities.
     *
     * @Route("/search", name="region_search", methods={"GET"})
     * @Template()
     *
     * @return array
     */
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
     * Creates a new Region entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="region_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            $this->addFlash('success', 'The new region was created.');

            return $this->redirectToRoute('region_show', ['id' => $region->getId()]);
        }

        return [
            'region' => $region,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Region entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="region_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Region entity.
     *
     * @return array
     *
     * @Route("/{id}", name="region_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Region $region) {
        return [
            'region' => $region,
        ];
    }

    /**
     * Displays a form to edit an existing Region entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="region_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Region $region) {
        $editForm = $this->createForm(RegionType::class, $region);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The region has been updated.');

            return $this->redirectToRoute('region_show', ['id' => $region->getId()]);
        }

        return [
            'region' => $region,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Region entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="region_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Region $region) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($region);
        $em->flush();
        $this->addFlash('success', 'The region was deleted.');

        return $this->redirectToRoute('region_index');
    }
}
