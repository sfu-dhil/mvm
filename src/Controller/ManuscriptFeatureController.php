<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ManuscriptFeature;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/manuscript_feature')]
class ManuscriptFeatureController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptFeature entities.
     *
     * @return array
     */
    #[Route(path: '/', name: 'manuscript_feature_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(ManuscriptFeature::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $manuscriptFeatures = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptFeatures' => $manuscriptFeatures,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'manuscript_feature_show', methods: ['GET'])]
    #[Template]
    public function showAction(ManuscriptFeature $manuscriptFeature) {
        return [
            'manuscriptFeature' => $manuscriptFeature,
        ];
    }
}
