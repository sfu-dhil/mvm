<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ImageUrl;
use App\Form\ImageUrlType;
use App\Repository\ImageUrlRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ImageUrl controller.
 *
 * @IsGranted("ROLE_USER")
 * @Route("/image_url")
 */
class ImageUrlController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ImageUrl entities.
     *
     * @return array
     *
     * @Route("/", name="image_url_index", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ImageUrl::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $imageUrls = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'imageUrls' => $imageUrls,
        ];
    }

    /**
     * Typeahead API endpoint for ImageUrl entities.
     *
     * To make this work, add something like this to ImageUrlRepository:
     *
     * @Route("/typeahead", name="image_url_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ImageUrlRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for ImageUrl entities.
     *
     * To make this work, add a method like this one to the
     * App:ImageUrl repository. Reregion the fieldName with
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
     * @Route("/search", name="image_url_search", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request, ImageUrlRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $imageUrls = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $imageUrls = [];
        }

        return [
            'imageUrls' => $imageUrls,
            'q' => $q,
        ];
    }

    /**
     * Creates a new ImageUrl entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="image_url_new", methods={"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request) {
        $imageUrl = new ImageUrl();
        $form = $this->createForm(ImageUrlType::class, $imageUrl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imageUrl);
            $em->flush();

            $this->addFlash('success', 'The new imageUrl was created.');

            return $this->redirectToRoute('image_url_show', ['id' => $imageUrl->getId()]);
        }

        return [
            'imageUrl' => $imageUrl,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new ImageUrl entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="image_url_new_popup", methods={"GET","POST"})
     * @Template()
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a ImageUrl entity.
     *
     * @return array
     *
     * @Route("/{id}", name="image_url_show", methods={"GET"})
     * @Template()
     */
    public function showAction(ImageUrl $imageUrl) {
        return [
            'imageUrl' => $imageUrl,
        ];
    }

    /**
     * Displays a form to edit an existing ImageUrl entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="image_url_edit", methods={"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, ImageUrl $imageUrl) {
        $editForm = $this->createForm(ImageUrlType::class, $imageUrl);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The imageUrl has been updated.');

            return $this->redirectToRoute('image_url_show', ['id' => $imageUrl->getId()]);
        }

        return [
            'imageUrl' => $imageUrl,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ImageUrl entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="image_url_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, ImageUrl $imageUrl) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($imageUrl);
        $em->flush();
        $this->addFlash('success', 'The imageUrl was deleted.');

        return $this->redirectToRoute('image_url_index');
    }
}
