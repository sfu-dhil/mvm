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
use AppBundle\Entity\PrintSource;
use AppBundle\Form\PrintSourceType;

/**
 * PrintSource controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/print_source")
 */
class PrintSourceController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all PrintSource entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="print_source_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(PrintSource::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $printSources = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'printSources' => $printSources,
        );
    }

/**
     * Typeahead API endpoint for PrintSource entities.
     *
     * To make this work, add something like this to PrintSourceRepository:
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
     * @Route("/typeahead", name="print_source_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(PrintSource::class);
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
     * Search for PrintSource entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:PrintSource repository. Reregion the fieldName with
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
     * @Route("/search", name="print_source_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:PrintSource');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $printSources = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $printSources = array();
	}

        return array(
            'printSources' => $printSources,
            'q' => $q,
        );
    }

    /**
     * Creates a new PrintSource entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="print_source_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $printSource = new PrintSource();
        $form = $this->createForm(PrintSourceType::class, $printSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($printSource);
            $em->flush();

            $this->addFlash('success', 'The new printSource was created.');
            return $this->redirectToRoute('print_source_show', array('id' => $printSource->getId()));
        }

        return array(
            'printSource' => $printSource,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new PrintSource entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="print_source_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a PrintSource entity.
     *
     * @param PrintSource $printSource
     *
     * @return array
     *
     * @Route("/{id}", name="print_source_show", methods={"GET"})
     * @Template()
     */
    public function showAction(PrintSource $printSource)
    {

        return array(
            'printSource' => $printSource,
        );
    }

    /**
     * Displays a form to edit an existing PrintSource entity.
     *
     *
     * @param Request $request
     * @param PrintSource $printSource
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="print_source_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, PrintSource $printSource)
    {
        $editForm = $this->createForm(PrintSourceType::class, $printSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The printSource has been updated.');
            return $this->redirectToRoute('print_source_show', array('id' => $printSource->getId()));
        }

        return array(
            'printSource' => $printSource,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a PrintSource entity.
     *
     *
     * @param Request $request
     * @param PrintSource $printSource
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="print_source_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, PrintSource $printSource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($printSource);
        $em->flush();
        $this->addFlash('success', 'The printSource was deleted.');

        return $this->redirectToRoute('print_source_index');
    }
}
