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
use AppBundle\Entity\ArchiveSource;
use AppBundle\Form\ArchiveSourceType;

/**
 * ArchiveSource controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/archive_source")
 */
class ArchiveSourceController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ArchiveSource entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="archive_source_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ArchiveSource::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $archiveSources = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'archiveSources' => $archiveSources,
        );
    }

/**
     * Typeahead API endpoint for ArchiveSource entities.
     *
     * To make this work, add something like this to ArchiveSourceRepository:
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
     * @Route("/typeahead", name="archive_source_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ArchiveSource::class);
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
     * Search for ArchiveSource entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ArchiveSource repository. Replace the fieldName with
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
     * @Route("/search", name="archive_source_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ArchiveSource');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $archiveSources = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $archiveSources = array();
	}

        return array(
            'archiveSources' => $archiveSources,
            'q' => $q,
        );
    }

    /**
     * Creates a new ArchiveSource entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="archive_source_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $archiveSource = new ArchiveSource();
        $form = $this->createForm(ArchiveSourceType::class, $archiveSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($archiveSource);
            $em->flush();

            $this->addFlash('success', 'The new archiveSource was created.');
            return $this->redirectToRoute('archive_source_show', array('id' => $archiveSource->getId()));
        }

        return array(
            'archiveSource' => $archiveSource,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ArchiveSource entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="archive_source_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ArchiveSource entity.
     *
     * @param ArchiveSource $archiveSource
     *
     * @return array
     *
     * @Route("/{id}", name="archive_source_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ArchiveSource $archiveSource)
    {

        return array(
            'archiveSource' => $archiveSource,
        );
    }

    /**
     * Displays a form to edit an existing ArchiveSource entity.
     *
     *
     * @param Request $request
     * @param ArchiveSource $archiveSource
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="archive_source_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ArchiveSource $archiveSource)
    {
        $editForm = $this->createForm(ArchiveSourceType::class, $archiveSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The archiveSource has been updated.');
            return $this->redirectToRoute('archive_source_show', array('id' => $archiveSource->getId()));
        }

        return array(
            'archiveSource' => $archiveSource,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArchiveSource entity.
     *
     *
     * @param Request $request
     * @param ArchiveSource $archiveSource
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="archive_source_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ArchiveSource $archiveSource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($archiveSource);
        $em->flush();
        $this->addFlash('success', 'The archiveSource was deleted.');

        return $this->redirectToRoute('archive_source_index');
    }
}
