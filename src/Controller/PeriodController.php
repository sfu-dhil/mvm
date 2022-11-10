<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Period;
use App\Form\PeriodType;
use App\Repository\PeriodRepository;
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
 * Period controller.
 *
 * @Route("/period")
 */
class PeriodController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Period entities.
     *
     * @return array
     *
     * @Route("/", name="period_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Period::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $periods = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'periods' => $periods,
        ];
    }

    /**
     * Typeahead API endpoint for Period entities.
     *
     * @Route("/typeahead", name="period_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PeriodRepository $repo) {
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
     * Creates a new Period entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="period_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $period = new Period();
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($period);
            $em->flush();

            $this->addFlash('success', 'The new period was created.');

            return $this->redirectToRoute('period_show', ['id' => $period->getId()]);
        }

        return [
            'period' => $period,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Period entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="period_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Period entity.
     *
     * @return array
     *
     * @Route("/{id}", name="period_show", methods={"GET"})
     * @Template
     */
    public function showAction(Period $period) {
        return [
            'period' => $period,
        ];
    }

    /**
     * Finds and displays a Period modal.
     *
     * @return array
     *
     * @Route("/{id}/modal", name="period_modal", methods={"GET"})
     * @Template
     */
    public function modalAction(Period $period) {
        return [
            'period' => $period,
        ];
    }

    /**
     * Displays a form to edit an existing Period entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="period_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Period $period) {
        $editForm = $this->createForm(PeriodType::class, $period);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The period has been updated.');

            return $this->redirectToRoute('period_show', ['id' => $period->getId()]);
        }

        return [
            'period' => $period,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Period entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="period_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Period $period) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($period);
        $em->flush();
        $this->addFlash('success', 'The period was deleted.');

        return $this->redirectToRoute('period_index');
    }
}
