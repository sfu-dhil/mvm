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
use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;

/**
 * Theme controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/theme")
 */
class ThemeController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Theme entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="theme_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(Theme::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $themes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'themes' => $themes,
        );
    }

/**
     * Typeahead API endpoint for Theme entities.
     *
     * To make this work, add something like this to ThemeRepository:
        //    public function typeaheadQuery($q) {
        //        $qb = $this->createQueryBuilder('e');
        //        $qb->andWhere("e.name LIKE :q");
        //        $qb->orderBy('e.name');
        //        $qb->setParameter('q', "{$q}%");
        //        return $qb->getQuery()->execute();
        //    }
     *
     * @param Request $request
     *
     * @Route("/typeahead", name="theme_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(Theme::class);
        $data = [];
        foreach($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result,
            ];
        }
        return new JsonResponse($data);
    }
    /**
     * Search for Theme entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:Theme repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * <code><pre>
     *    public function searchQuery($q) {
     *       $qb = $this->createQueryBuilder('e');
     *       $qb->addSelect("MATCH (e.title) AGAINST(:q BOOLEAN) as HIDDEN score");
     *       $qb->orderBy('score', 'DESC');
     *       $qb->setParameter('q', $q);
     *       return $qb->getQuery();
     *    }
     * </pre></code>
     *
     * @param Request $request
     *
     * @Route("/search", name="theme_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:Theme');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $themes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $themes = array();
	}

        return array(
            'themes' => $themes,
            'q' => $q,
        );
    }

    /**
     * Creates a new Theme entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="theme_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            $this->addFlash('success', 'The new theme was created.');
            return $this->redirectToRoute('theme_show', array('id' => $theme->getId()));
        }

        return array(
            'theme' => $theme,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Theme entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="theme_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @param Theme $theme
     *
     * @return array
     *
     * @Route("/{id}", name="theme_show", methods={"GET"})
     * @Template()
     */
    public function showAction(Theme $theme)
    {

        return array(
            'theme' => $theme,
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     *
     * @param Request $request
     * @param Theme $theme
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="theme_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, Theme $theme)
    {
        $editForm = $this->createForm(ThemeType::class, $theme);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The theme has been updated.');
            return $this->redirectToRoute('theme_show', array('id' => $theme->getId()));
        }

        return array(
            'theme' => $theme,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Theme entity.
     *
     *
     * @param Request $request
     * @param Theme $theme
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="theme_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, Theme $theme)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($theme);
        $em->flush();
        $this->addFlash('success', 'The theme was deleted.');

        return $this->redirectToRoute('theme_index');
    }
}
