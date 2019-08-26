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
use AppBundle\Entity\ManuscriptContent;
use AppBundle\Form\ManuscriptContentType;

/**
 * ManuscriptContent controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/manuscript_content")
 */
class ManuscriptContentController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ManuscriptContent entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="manuscript_content_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ManuscriptContent::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $manuscriptContents = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'manuscriptContents' => $manuscriptContents,
        );
    }

    /**
     * Finds and displays a ManuscriptContent entity.
     *
     * @param ManuscriptContent $manuscriptContent
     *
     * @return array
     *
     * @Route("/{id}", name="manuscript_content_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ManuscriptContent $manuscriptContent)
    {

        return array(
            'manuscriptContent' => $manuscriptContent,
        );
    }

}
