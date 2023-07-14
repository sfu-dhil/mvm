<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ContentContribution;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/content_contribution')]
class ContentContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'content_contribution_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) : array {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(ContentContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $contentContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'contentContributions' => $contentContributions,
        ];
    }

    #[Route(path: '/{id}', name: 'content_contribution_show', methods: ['GET'])]
    #[Template]
    public function showAction(ContentContribution $contentContribution) : array {
        return [
            'contentContribution' => $contentContribution,
        ];
    }
}
