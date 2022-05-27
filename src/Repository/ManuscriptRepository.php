<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Manuscript;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ManuscriptRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ManuscriptRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Manuscript::class);
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.title LIKE :q');
        $qb->orWhere('e.callNumber like :q');
        $qb->orderBy('e.title');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery();
    }

    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $matches = [];
        if (preg_match('/^\s*"(.*?)"\s*$/u', $q, $matches)) {
            $qb->where('e.callNumber LIKE :q');
            $qb->orWhere('e.title like :q');
            $qb->setParameter('q', "%${matches[1]}%");
        } else {
            $qb->where('MATCH(e.callNumber, e.description, e.format) AGAINST (:q BOOLEAN) > 0.0');
            $qb->setParameter('q', $q);
        }
        $qb->orderBy('e.callNumber', 'ASC');

        return $qb->getQuery();
    }
}
