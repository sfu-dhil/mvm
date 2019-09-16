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
use AppBundle\Entity\ContentRole;
use AppBundle\Form\ContentRoleType;

/**
 * ContentRole controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/content_role")
 */
class ContentRoleController extends Controller implements PaginatorAwareInterface {

    use PaginatorTrait;

    /**
     * Lists all ContentRole entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="content_role_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ContentRole::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $contentRoles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'contentRoles' => $contentRoles,
        );
    }

    /**
     * Typeahead API endpoint for ContentRole entities.
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="content_role_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(ContentRole::class);
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
     * Creates a new ContentRole entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="content_role_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $contentRole = new ContentRole();
        $form = $this->createForm(ContentRoleType::class, $contentRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRole);
            $em->flush();

            $this->addFlash('success', 'The new contentRole was created.');

            return $this->redirectToRoute('content_role_show', array('id' => $contentRole->getId()));
        }

        return array(
            'contentRole' => $contentRole,
            'form'        => $form->createView(),
        );
    }

    /**
     * Creates a new ContentRole entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="content_role_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ContentRole entity.
     *
     * @param ContentRole $contentRole
     *
     * @return array
     *
     * @Route("/{id}", name="content_role_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ContentRole $contentRole) {

        return array(
            'contentRole' => $contentRole,
        );
    }

    /**
     * Displays a form to edit an existing ContentRole entity.
     *
     *
     * @param Request $request
     * @param ContentRole $contentRole
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="content_role_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ContentRole $contentRole) {
        $editForm = $this->createForm(ContentRoleType::class, $contentRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contentRole has been updated.');

            return $this->redirectToRoute('content_role_show', array('id' => $contentRole->getId()));
        }

        return array(
            'contentRole' => $contentRole,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a ContentRole entity.
     *
     *
     * @param Request $request
     * @param ContentRole $contentRole
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="content_role_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ContentRole $contentRole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contentRole);
        $em->flush();
        $this->addFlash('success', 'The contentRole was deleted.');

        return $this->redirectToRoute('content_role_index');
    }
}
