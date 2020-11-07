<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ManuscriptContribution;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ManuscriptContribution controller.
 *
 * @Route("/manuscript_contribution")
 */
class ManuscriptContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ManuscriptContribution entities.
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

        $manuscriptContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'manuscriptContributions' => $manuscriptContributions,
        ];
    }

    /**
     * Finds and displays a ManuscriptContribution entity.
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_contribution_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptContribution $manuscriptContribution) {
        return [
            'manuscriptContribution' => $manuscriptContribution,
        ];
    }
}
