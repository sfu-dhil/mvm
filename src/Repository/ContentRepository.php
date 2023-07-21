<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ContentRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Content::class);
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.title LIKE :q');
        $qb->orWhere('e.firstLine like :q');
        $qb->orderBy('e.title');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery();
    }

    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');

        // join the contributions, person, and role tables to query the author
        $qb->leftJoin('e.contributions', 'c');
        $qb->leftJoin('c.person', 'p');
        $qb->leftJoin('c.role', 'r');

        // content matches
        $qb->where('MATCH (e.firstLine, e.transcription, e.description) AGAINST(:q BOOLEAN) > 0.0');

        // author matches
        $qb->orWhere(
            $qb->expr()->andX(
                $qb->expr()->gt('MATCH(p.fullName, p.variantNames) AGAINST (:q BOOLEAN)', 0.0),
                $qb->expr()->eq('r.name', '\'author\'')
            )
        );

        $qb->orderBy('e.firstLine');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
