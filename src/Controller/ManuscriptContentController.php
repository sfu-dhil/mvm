<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ManuscriptContent;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptContent controller.
 *
 * @Route("/manuscript_content")
 */
class ManuscriptContentController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptContent entities.
     *
     * @return array
     *
     * @Route("/", name="manuscript_content_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
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
     * Finds and displays a ManuscriptContent entity.
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_content_show", methods={"GET"})
     * @Template
     */
    public function showAction(ManuscriptContent $manuscriptContent) {
        return [
            'manuscriptContent' => $manuscriptContent,
        ];
    }
}
