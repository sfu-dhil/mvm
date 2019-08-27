<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ContentContribution;
use AppBundle\Form\ContentContributionType;

/**
 * ContentContribution controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/content_contribution")
 */
class ContentContributionController extends Controller implements PaginatorAwareInterface {

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
        $paginator = $this->get('knp_paginator');
        $contentContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'contentContributions' => $contentContributions,
        );
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

        return array(
            'contentContribution' => $contentContribution,
        );
    }

}
