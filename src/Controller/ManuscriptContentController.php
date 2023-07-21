<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ManuscriptContent;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptContent controller.
 */
#[Route(path: '/manuscript_content')]
class ManuscriptContentController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'manuscript_content_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')
            ->from(ManuscriptContent::class, 'e')
            ->leftJoin('e.content', 'c')
            ->leftJoin('e.manuscript', 'm')
            ->orderBy('c.firstLine', 'ASC')
            ->addOrderBy('m.callNumber')
        ;
        $query = $qb->getQuery();

        $manuscriptContents = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptContents' => $manuscriptContents,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'manuscript_content_show', methods: ['GET'])]
    #[Template]
    public function showAction(ManuscriptContent $manuscriptContent) {
        return [
            'manuscriptContent' => $manuscriptContent,
        ];
    }
}
