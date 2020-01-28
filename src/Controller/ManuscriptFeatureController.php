<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ManuscriptFeature;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptFeature controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_feature")
 */
class ManuscriptFeatureController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptFeature entities.
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

        $manuscriptFeatures = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptFeatures' => $manuscriptFeatures,
        ];
    }

    /**
     * Finds and displays a ManuscriptFeature entity.
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_feature_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptFeature $manuscriptFeature) {
        return [
            'manuscriptFeature' => $manuscriptFeature,
        ];
    }
}
