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
use AppBundle\Entity\ImageFile;
use AppBundle\Form\ImageFileType;

/**
 * ImageFile controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/image_file")
 */
class ImageFileController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ImageFile entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="image_file_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ImageFile::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $imageFiles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'imageFiles' => $imageFiles,
        );
    }

/**
     * Typeahead API endpoint for ImageFile entities.
     *
     * To make this work, add something like this to ImageFileRepository:
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
     * @Route("/typeahead", name="image_file_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ImageFile::class);
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
     * Search for ImageFile entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ImageFile repository. Replace the fieldName with
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
     * @Route("/search", name="image_file_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ImageFile');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $imageFiles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $imageFiles = array();
	}

        return array(
            'imageFiles' => $imageFiles,
            'q' => $q,
        );
    }

    /**
     * Creates a new ImageFile entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="image_file_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $imageFile = new ImageFile();
        $form = $this->createForm(ImageFileType::class, $imageFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imageFile);
            $em->flush();

            $this->addFlash('success', 'The new imageFile was created.');
            return $this->redirectToRoute('image_file_show', array('id' => $imageFile->getId()));
        }

        return array(
            'imageFile' => $imageFile,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ImageFile entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="image_file_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ImageFile entity.
     *
     * @param ImageFile $imageFile
     *
     * @return array
     *
     * @Route("/{id}", name="image_file_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ImageFile $imageFile)
    {

        return array(
            'imageFile' => $imageFile,
        );
    }

    /**
     * Displays a form to edit an existing ImageFile entity.
     *
     *
     * @param Request $request
     * @param ImageFile $imageFile
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="image_file_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ImageFile $imageFile)
    {
        $editForm = $this->createForm(ImageFileType::class, $imageFile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The imageFile has been updated.');
            return $this->redirectToRoute('image_file_show', array('id' => $imageFile->getId()));
        }

        return array(
            'imageFile' => $imageFile,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ImageFile entity.
     *
     *
     * @param Request $request
     * @param ImageFile $imageFile
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="image_file_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ImageFile $imageFile)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($imageFile);
        $em->flush();
        $this->addFlash('success', 'The imageFile was deleted.');

        return $this->redirectToRoute('image_file_index');
    }
}
