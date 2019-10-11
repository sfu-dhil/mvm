<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ManuscriptContribution;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptContribution controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_contribution")
 */
class ManuscriptContributionController extends Controller implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptContribution entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_contribution_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptContribution::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptContributions' => $manuscriptContributions,
        );
    }

    /**
     * Finds and displays a ManuscriptContribution entity.
     *
     * @param ManuscriptContribution $manuscriptContribution
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_contribution_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptContribution $manuscriptContribution) {
        return array(
            'manuscriptContribution' => $manuscriptContribution,
        );
    }
}
