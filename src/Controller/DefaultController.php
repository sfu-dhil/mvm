<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Period;
use App\Form\PersonTypeaheadType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\BlogBundle\Entity\Page;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'homepage')]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $archives = $entityManager->getRepository(Archive::class)->findBy([], ['label' => 'ASC']);
        $periods = $entityManager->getRepository(Period::class)->findBy([], ['label' => 'ASC']);

        $pageRepo = $entityManager->getRepository(Page::class);
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
}
