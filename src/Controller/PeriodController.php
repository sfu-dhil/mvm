<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Period;
use App\Form\PeriodType;
use App\Repository\PeriodRepository;
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

#[Route(path: '/period')]
class PeriodController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'period_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Period::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $periods = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'periods' => $periods,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'period_typeahead', methods: ['GET'])]
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
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'period_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $period = new Period();
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($period);
            $entityManager->flush();

            $this->addFlash('success', 'The new period was created.');

            return $this->redirectToRoute('period_show', ['id' => $period->getId()]);
        }

        return [
            'period' => $period,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'period_show', methods: ['GET'])]
    #[Template]
    public function showAction(Period $period) {
        return [
            'period' => $period,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'period_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(Period $period) {
        return [
            'period' => $period,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'period_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Period $period) {
        $editForm = $this->createForm(PeriodType::class, $period);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The period has been updated.');

            return $this->redirectToRoute('period_show', ['id' => $period->getId()]);
        }

        return [
            'period' => $period,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'period_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Period $period) {
        $entityManager->remove($period);
        $entityManager->flush();
        $this->addFlash('success', 'The period was deleted.');

        return $this->redirectToRoute('period_index');
    }
}
