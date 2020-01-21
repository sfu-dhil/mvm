<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ContentContribution;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ContentContribution controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/content_contribution")
 */
class ContentContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ContentContribution entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="content_contribution_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ContentContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $contentContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'contentContributions' => $contentContributions,
        ];
    }

    /**
     * Finds and displays a ContentContribution entity.
     *
     * @param ContentContribution $contentContribution
     *
     * @return array
     *
     * @Route("/{id}", name="content_contribution_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ContentContribution $contentContribution) {
        return [
            'contentContribution' => $contentContribution,
        ];
    }
}
