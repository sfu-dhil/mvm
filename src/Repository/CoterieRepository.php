<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Coterie;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Repository\TermRepository;

/**
 * @method null|Coterie find($id, $lockMode = null, $lockVersion = null)
 * @method null|Coterie findOneBy(array $criteria, array $orderBy = null)
 * @method Coterie[] findAll()
 * @method Coterie[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoterieRepository extends TermRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Coterie::class);
    }

    public function searchQuery(string $q) : Query {
        $qb = $this->createQueryBuilder('coterie');
        $qb->leftJoin('coterie.regions', 'region');
        $qb->where('MATCH(coterie.label, coterie.description) AGAINST (:q BOOLEAN) > 0.0');
        $qb->orWhere('MATCH(region.name) AGAINST (:q BOOLEAN) > 0.0');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
