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
use AppBundle\Entity\ManuscriptRole;
use AppBundle\Form\ManuscriptRoleType;

/**
 * ManuscriptRole controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_role")
 */
class ManuscriptRoleController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all ManuscriptRole entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_role_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptRole::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptRoles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptRoles' => $manuscriptRoles,
        );
    }

    /**
     * Typeahead API endpoint for ManuscriptRole entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="manuscript_role_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(ManuscriptRole::class);
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
     * Creates a new ManuscriptRole entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_role_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $manuscriptRole = new ManuscriptRole();
        $form = $this->createForm(ManuscriptRoleType::class, $manuscriptRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manuscriptRole);
            $em->flush();

            $this->addFlash('success', 'The new manuscriptRole was created.');

            return $this->redirectToRoute('manuscript_role_show', array('id' => $manuscriptRole->getId()));
        }

        return array(
            'manuscriptRole' => $manuscriptRole,
            'form'           => $form->createView(),
        );
    }

    /**
     * Creates a new ManuscriptRole entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_role_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ManuscriptRole entity.
     *
     * @param ManuscriptRole $manuscriptRole
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_role_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptRole $manuscriptRole) {

        return array(
            'manuscriptRole' => $manuscriptRole,
        );
    }

    /**
     * Displays a form to edit an existing ManuscriptRole entity.
     *
     *
     * @param Request $request
     * @param ManuscriptRole $manuscriptRole
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_role_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ManuscriptRole $manuscriptRole) {
        $editForm = $this->createForm(ManuscriptRoleType::class, $manuscriptRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscriptRole has been updated.');

            return $this->redirectToRoute('manuscript_role_show', array('id' => $manuscriptRole->getId()));
        }

        return array(
            'manuscriptRole' => $manuscriptRole,
            'edit_form'      => $editForm->createView(),
        );
    }

    /**
     * Deletes a ManuscriptRole entity.
     *
     *
     * @param Request $request
     * @param ManuscriptRole $manuscriptRole
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="manuscript_role_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ManuscriptRole $manuscriptRole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manuscriptRole);
        $em->flush();
        $this->addFlash('success', 'The manuscriptRole was deleted.');

        return $this->redirectToRoute('manuscript_role_index');
    }
}
