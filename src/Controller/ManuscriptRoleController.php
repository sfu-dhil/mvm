<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ManuscriptRole;
use App\Form\ManuscriptRoleType;
use App\Repository\ManuscriptRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/manuscript_role')]
class ManuscriptRoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'manuscript_role_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(ManuscriptRole::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $manuscriptRoles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptRoles' => $manuscriptRoles,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'manuscript_role_typeahead', methods: ['GET'])]
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
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'manuscript_role_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $manuscriptRole = new ManuscriptRole();
        $form = $this->createForm(ManuscriptRoleType::class, $manuscriptRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($manuscriptRole);
            $entityManager->flush();

            $this->addFlash('success', 'The new manuscriptRole was created.');

            return $this->redirectToRoute('manuscript_role_show', ['id' => $manuscriptRole->getId()]);
        }

        return [
            'manuscriptRole' => $manuscriptRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'manuscript_role_show', methods: ['GET'])]
    #[Template]
    public function showAction(ManuscriptRole $manuscriptRole) {
        return [
            'manuscriptRole' => $manuscriptRole,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'manuscript_role_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, ManuscriptRole $manuscriptRole) {
        $editForm = $this->createForm(ManuscriptRoleType::class, $manuscriptRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The manuscriptRole has been updated.');

            return $this->redirectToRoute('manuscript_role_show', ['id' => $manuscriptRole->getId()]);
        }

        return [
            'manuscriptRole' => $manuscriptRole,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'manuscript_role_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, ManuscriptRole $manuscriptRole) {
        $entityManager->remove($manuscriptRole);
        $entityManager->flush();
        $this->addFlash('success', 'The manuscriptRole was deleted.');

        return $this->redirectToRoute('manuscript_role_index');
    }
}
