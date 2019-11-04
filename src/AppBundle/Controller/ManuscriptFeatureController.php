<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ManuscriptFeature;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptFeature controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_feature")
 */
class ManuscriptFeatureController extends Controller implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptFeature entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_feature_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptFeature::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptFeatures = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptFeatures' => $manuscriptFeatures,
        );
    }

    /**
     * Finds and displays a ManuscriptFeature entity.
     *
     * @param ManuscriptFeature $manuscriptFeature
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_feature_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptFeature $manuscriptFeature) {
        return array(
            'manuscriptFeature' => $manuscriptFeature,
        );
    }
}
