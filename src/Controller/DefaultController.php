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
use App\Repository\ArchiveRepository;
use App\Entity\Period;
use App\Form\PeriodType;
use App\Repository\PeriodRepository;
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
        
        $archiveQuery = $em->createQueryBuilder()->select('e')->from(Archive::class,'e')->orderBy('e.label','ASC');
        $periodQuery =  $em->createQueryBuilder()->select('e')->from(Period::class,'e')->orderBy('e.label','ASC');
        
        $archives = $archiveQuery->getQuery()->execute();
        $periods = $periodQuery->getQuery()->execute();
        
        return [
            'archives' => $archives,
            'periods' => $periods
        ];
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     */
    public function privacyAction(Request $request) : void {
    }
}
