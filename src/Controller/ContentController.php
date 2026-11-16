<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentContributionsType;
use App\Form\ContentType;
use App\Repository\ContentRepository;
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

#[Route(path: '/content')]
class ContentController extends AbstractController {
    #[Route(path: '/', name: 'content_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) : array {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Content::class, 'e')->orderBy('e.firstLine', 'ASC')->addOrderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $contents = $query->execute();

        return [
            'contents' => $contents,
        ];
    }

    // public function searchQuery($q) {
    //     $qb = $this->createQueryBuilder('e');

    //     // join the contributions, person, and role tables to query the author
    //     $qb->leftJoin('e.contributions', 'c');
    //     $qb->leftJoin('c.person', 'p');
    //     $qb->leftJoin('c.role', 'r');

    //     // content matches
    //     $qb->where('MATCH (e.firstLine, e.transcription, e.description) AGAINST(:q BOOLEAN) > 0.0');

    //     // author matches
    //     $qb->orWhere(
    //         $qb->expr()->andX(
    //             $qb->expr()->gt('MATCH(p.fullName, p.variantNames) AGAINST (:q BOOLEAN)', 0.0),
    //             $qb->expr()->eq('r.name', '\'author\'')
    //         )
    //     );

    //     $qb->orderBy('e.firstLine');
    //     $qb->setParameter('q', $q);

    //     return $qb->getQuery();
    // }

    #[Route(path: '/new', name: 'content_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) : array|RedirectResponse {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($content);
            $entityManager->flush();

            $this->addFlash('success', 'The new content was created.');

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'content_show', methods: ['GET'])]
    #[Template]
    public function showAction(Content $content) : array {
        return [
            'content' => $content,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'content_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Content $content) : array|RedirectResponse {
        $editForm = $this->createForm(ContentType::class, $content);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The content has been updated.');

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'content_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Content $content) : array|RedirectResponse {
        $entityManager->remove($content);
        $entityManager->flush();
        $this->addFlash('success', 'The content was deleted.');

        return $this->redirectToRoute('content_index');
    }

    #[Route(path: '/{id}/contributions', name: 'content_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function contributionsAction(EntityManagerInterface $entityManager, Request $request, Content $content) : array|RedirectResponse {
        $editForm = $this->createForm(ContentContributionsType::class, $content);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            foreach ($content->getContributions() as $contribution) {
                $contribution->setContent($content);
            }
            $entityManager->flush();
            $this->addFlash('success', 'The content has been updated.');

            return $this->redirectToRoute('content_show', ['id' => $content->getId()]);
        }

        return [
            'content' => $content,
            'edit_form' => $editForm->createView(),
        ];
    }
}
