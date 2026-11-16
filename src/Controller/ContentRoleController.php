<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ContentRole;
use App\Form\ContentRoleType;
use App\Repository\ContentRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/content_role')]
class ContentRoleController extends AbstractController {
    /**
     * @return array
     */
    #[Route(path: '/', name: 'content_role_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(ContentRole::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $contentRoles = $query->execute();

        return [
            'contentRoles' => $contentRoles,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'content_role_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $contentRole = new ContentRole();
        $form = $this->createForm(ContentRoleType::class, $contentRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contentRole);
            $entityManager->flush();

            $this->addFlash('success', 'The new contentRole was created.');

            return $this->redirectToRoute('content_role_show', ['id' => $contentRole->getId()]);
        }

        return [
            'contentRole' => $contentRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'content_role_show', methods: ['GET'])]
    #[Template]
    public function showAction(ContentRole $contentRole) {
        return [
            'contentRole' => $contentRole,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'content_role_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, ContentRole $contentRole) {
        $editForm = $this->createForm(ContentRoleType::class, $contentRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The contentRole has been updated.');

            return $this->redirectToRoute('content_role_show', ['id' => $contentRole->getId()]);
        }

        return [
            'contentRole' => $contentRole,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'content_role_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, ContentRole $contentRole) {
        $entityManager->remove($contentRole);
        $entityManager->flush();
        $this->addFlash('success', 'The contentRole was deleted.');

        return $this->redirectToRoute('content_role_index');
    }
}
