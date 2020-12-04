<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Coterie;
use App\Form\CoterieType;
use App\Repository\CoterieRepository;
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
 * @Route("/coterie")
 */
class CoterieController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="coterie_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, CoterieRepository $coterieRepository) : array {
        $query = $coterieRepository->indexQuery();
        $coteries = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'coteries' => $coteries,
        ];
    }

    /**
     * @Route("/search", name="coterie_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
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
     * @Route("/typeahead", name="coterie_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, CoterieRepository $coterieRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($coterieRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="coterie_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $coterie = new Coterie();
        $form = $this->createForm(CoterieType::class, $coterie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
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
     * @Route("/new_popup", name="coterie_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="coterie_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Coterie $coterie) {
        return [
            'coterie' => $coterie,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="coterie_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Coterie $coterie) {
        $form = $this->createForm(CoterieType::class, $coterie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated coterie has been saved.');

            return $this->redirectToRoute('coterie_show', ['id' => $coterie->getId()]);
        }

        return [
            'coterie' => $coterie,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="coterie_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Coterie $coterie) {
        if ($this->isCsrfTokenValid('delete' . $coterie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coterie);
            $entityManager->flush();
            $this->addFlash('success', 'The coterie has been deleted.');
        }

        return $this->redirectToRoute('coterie_index');
    }
}
