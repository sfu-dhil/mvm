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
use AppBundle\Entity\ImageUrl;
use AppBundle\Form\ImageUrlType;

/**
 * ImageUrl controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/image_url")
 */
class ImageUrlController extends Controller implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all ImageUrl entities.
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/", name="image_url_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ImageUrl::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $imageUrls = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'imageUrls' => $imageUrls,
        );
    }

/**
     * Typeahead API endpoint for ImageUrl entities.
     *
     * To make this work, add something like this to ImageUrlRepository:
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
     * @Route("/typeahead", name="image_url_typeahead", methods={"GET"})
     * @return JsonResponse
     */
    public function typeahead(Request $request)
    {
        $q = $request->query->get('q');
        if( ! $q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
	    $repo = $em->getRepository(ImageUrl::class);
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
     * Search for ImageUrl entities.
     *
     * To make this work, add a method like this one to the
     * AppBundle:ImageUrl repository. Reregion the fieldName with
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
     * @Route("/search", name="image_url_search", methods={"GET"})
     * @Template()
    * @return array
    */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('AppBundle:ImageUrl');
	$q = $request->query->get('q');
	if($q) {
	    $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $imageUrls = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
	} else {
            $imageUrls = array();
	}

        return array(
            'imageUrls' => $imageUrls,
            'q' => $q,
        );
    }

    /**
     * Creates a new ImageUrl entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="image_url_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $imageUrl = new ImageUrl();
        $form = $this->createForm(ImageUrlType::class, $imageUrl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imageUrl);
            $em->flush();

            $this->addFlash('success', 'The new imageUrl was created.');
            return $this->redirectToRoute('image_url_show', array('id' => $imageUrl->getId()));
        }

        return array(
            'imageUrl' => $imageUrl,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new ImageUrl entity in a popup.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="image_url_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request)
    {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ImageUrl entity.
     *
     * @param ImageUrl $imageUrl
     *
     * @return array
     *
     * @Route("/{id}", name="image_url_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ImageUrl $imageUrl)
    {

        return array(
            'imageUrl' => $imageUrl,
        );
    }

    /**
     * Displays a form to edit an existing ImageUrl entity.
     *
     *
     * @param Request $request
     * @param ImageUrl $imageUrl
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="image_url_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ImageUrl $imageUrl)
    {
        $editForm = $this->createForm(ImageUrlType::class, $imageUrl);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The imageUrl has been updated.');
            return $this->redirectToRoute('image_url_show', array('id' => $imageUrl->getId()));
        }

        return array(
            'imageUrl' => $imageUrl,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ImageUrl entity.
     *
     *
     * @param Request $request
     * @param ImageUrl $imageUrl
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="image_url_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ImageUrl $imageUrl)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($imageUrl);
        $em->flush();
        $this->addFlash('success', 'The imageUrl was deleted.');

        return $this->redirectToRoute('image_url_index');
    }
}
