<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/person')]
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @return array
     */
    #[Route(path: '/', name: 'person_index', methods: ['GET'])]
    #[Template]
    public function indexAction(EntityManagerInterface $entityManager, Request $request) {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')->from(Person::class, 'e')->orderBy('e.sortableName', 'ASC');
        $query = $qb->getQuery();

        $people = $this->paginator->paginate($query, $request->query->getint('page', 1), 24);

        return [
            'people' => $people,
        ];
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/typeahead', name: 'person_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q)->execute() as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @return array
     */
    #[Route(path: '/search', name: 'person_search', methods: ['GET'])]
    #[Template]
    public function searchAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 24);
        } else {
            $people = [];
        }

        return [
            'people' => $people,
            'q' => $q,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/new', name: 'person_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function newAction(EntityManagerInterface $entityManager, Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'The new person was created.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}', name: 'person_show', methods: ['GET'])]
    #[Template]
    public function showAction(Person $person) {
        return [
            'person' => $person,
        ];
    }

    /**
     * @return array
     */
    #[Route(path: '/{id}/modal', name: 'person_modal', methods: ['GET'])]
    #[Template]
    public function modalAction(Person $person) {
        return [
            'person' => $person,
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/edit', name: 'person_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Person $person) {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The person has been updated.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @return array|RedirectResponse
     */
    #[Route(path: '/{id}/delete', name: 'person_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function deleteAction(EntityManagerInterface $entityManager, Request $request, Person $person) {
        $entityManager->remove($person);
        $entityManager->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }
}
