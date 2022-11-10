<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ManuscriptRole;
use App\Form\ManuscriptRoleType;
use App\Repository\ManuscriptRoleRepository;
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
 * ManuscriptRole controller.
 *
 * @Route("/manuscript_role")
 */
class ManuscriptRoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptRole entities.
     *
     * @return array
     *
     * @Route("/", name="manuscript_role_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptRole::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $manuscriptRoles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptRoles' => $manuscriptRoles,
        ];
    }

    /**
     * Typeahead API endpoint for ManuscriptRole entities.
     *
     * @Route("/typeahead", name="manuscript_role_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ManuscriptRoleRepository $repo) {
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
     * Creates a new ManuscriptRole entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="manuscript_role_new", methods={"GET", "POST"})
     * @Template
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

            return $this->redirectToRoute('manuscript_role_show', ['id' => $manuscriptRole->getId()]);
        }

        return [
            'manuscriptRole' => $manuscriptRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new ManuscriptRole entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="manuscript_role_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ManuscriptRole entity.
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_role_show", methods={"GET"})
     * @Template
     */
    public function showAction(ManuscriptRole $manuscriptRole) {
        return [
            'manuscriptRole' => $manuscriptRole,
        ];
    }

    /**
     * Displays a form to edit an existing ManuscriptRole entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="manuscript_role_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, ManuscriptRole $manuscriptRole) {
        $editForm = $this->createForm(ManuscriptRoleType::class, $manuscriptRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The manuscriptRole has been updated.');

            return $this->redirectToRoute('manuscript_role_show', ['id' => $manuscriptRole->getId()]);
        }

        return [
            'manuscriptRole' => $manuscriptRole,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ManuscriptRole entity.
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
