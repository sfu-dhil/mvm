<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Archive;
use App\Form\ArchiveType;
use App\Form\PersonTypeaheadType;
use App\Repository\ArchiveRepository;
use App\Entity\Period;
use App\Form\PeriodType;
use App\Repository\PeriodRepository;
use Nines\BlogBundle\Entity;
use Nines\BlogBundle\Entity\Page;
use Nines\BlogBundle\Entity\Post;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $archives = $em->getRepository(Archive::class)->findBy([], ['label' => 'ASC']);
        $periods = $em->getRepository(Period::class)->findBy([], ['label' => 'ASC']);

        $pageRepo = $em->getRepository(Page::class);
        $form = $this->createForm(PersonTypeaheadType::class);

        $base = $this->getParameter('router.request_context.base_url');

        return [
            'archives' => $archives,
            'periods' => $periods,
            'homepage' => $pageRepo->findHomepage(),
            'form' => $form->createView(),
            'base' => $base,
        ];
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     */
    public function privacyAction(Request $request) : void {
    }
}
