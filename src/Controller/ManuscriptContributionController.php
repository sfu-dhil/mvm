<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ManuscriptContribution;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/manuscript_contribution')]
class ManuscriptContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'manuscript_contribution_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(ManuscriptContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $manuscriptContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptContributions' => $manuscriptContributions,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'manuscript_contribution_show', methods: ['GET'])]
    #[Template]
    public function showAction(ManuscriptContribution $manuscriptContribution) {
        return [
            'manuscriptContribution' => $manuscriptContribution,
        ];
    }
}
