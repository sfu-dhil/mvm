<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\PrintSource;
use App\Form\PrintSourceType;
use App\Repository\PrintSourceRepository;
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
 * PrintSource controller.
 *
 * @Route("/print_source")
 */
class PrintSourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all PrintSource entities.
     *
     * @return array
     *
     * @Route("/", name="print_source_index", methods={"GET"})
     * @Template
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(PrintSource::class, 'e')->orderBy('e.label', 'ASC');
        $query = $qb->getQuery();

        $printSources = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'printSources' => $printSources,
        ];
    }

    /**
     * Typeahead API endpoint for PrintSource entities.
     *
     * To make this work, add something like this to PrintSourceRepository:
     *
     * @Route("/typeahead", name="print_source_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PrintSourceRepository $repo) {
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
     * Search for PrintSource entities.
     *
     * To make this work, add a method like this one to the
     * App:PrintSource repository. Reregion the fieldName with
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
     * @Route("/search", name="print_source_search", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, PrintSourceRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $printSources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $printSources = [];
        }

        return [
            'printSources' => $printSources,
            'q' => $q,
        ];
    }

    /**
     * Creates a new PrintSource entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new", name="print_source_new", methods={"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request) {
        $printSource = new PrintSource();
        $form = $this->createForm(PrintSourceType::class, $printSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($printSource);
            $em->flush();

            $this->addFlash('success', 'The new printSource was created.');

            return $this->redirectToRoute('print_source_show', ['id' => $printSource->getId()]);
        }

        return [
            'printSource' => $printSource,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new PrintSource entity in a popup.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/new_popup", name="print_source_new_popup", methods={"GET", "POST"})
     * @Template
     */
    public function newPopupAction(Request $request) {
        return $this->newAction($request);
    }

    /**
     * Finds and displays a PrintSource entity.
     *
     * @return array
     *
     * @Route("/{id}", name="print_source_show", methods={"GET"})
     * @Template
     */
    public function showAction(PrintSource $printSource) {
        return [
            'printSource' => $printSource,
        ];
    }

    /**
     * Displays a form to edit an existing PrintSource entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="print_source_edit", methods={"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, PrintSource $printSource) {
        $editForm = $this->createForm(PrintSourceType::class, $printSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The printSource has been updated.');

            return $this->redirectToRoute('print_source_show', ['id' => $printSource->getId()]);
        }

        return [
            'printSource' => $printSource,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a PrintSource entity.
     *
     * @return array|RedirectResponse
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/delete", name="print_source_delete", methods={"GET"})
     */
    public function deleteAction(Request $request, PrintSource $printSource) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($printSource);
        $em->flush();
        $this->addFlash('success', 'The printSource was deleted.');

        return $this->redirectToRoute('print_source_index');
    }
}
