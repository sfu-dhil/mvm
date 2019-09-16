<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Period;
use AppBundle\Form\PeriodType;

/**
 * Period controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/period")
 */
class PeriodController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all Period entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="period_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Period::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $periods = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'periods' => $periods,
        );
    }

    /**
     * Typeahead API endpoint for Period entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="period_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Period::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id'   => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Period entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="period_new", methods={"GET","POST"})
     * @Template()
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

            return $this->redirectToRoute('period_show', array('id' => $period->getId()));
        }

        return array(
            'period' => $period,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Period entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="period_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Period entity.
     *
     * @param Period $period
     *
     * @return array
     *
     * @Route("/{id}", name="period_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Period $period) {

        return array(
            'period' => $period,
        );
    }

    /**
     * Displays a form to edit an existing Period entity.
     *
     *
     * @param Request $request
     * @param Period $period
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="period_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Period $period) {
        $editForm = $this->createForm(PeriodType::class, $period);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The period has been updated.');

            return $this->redirectToRoute('period_show', array('id' => $period->getId()));
        }

        return array(
            'period'    => $period,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Period entity.
     *
     *
     * @param Request $request
     * @param Period $period
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
