<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Manuscript;
use Doctrine\Persistence\ManagerRegistry;
use Nines\UtilBundle\Services\Text;

/**
 * ManuscriptRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ManuscriptRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Manuscript::class);
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.title LIKE :q');
        $qb->orWhere('e.callNumber like :q');
        $qb->orderBy('e.title');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery()->execute();
    }

    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $matches = [];
        if(preg_match('/^\s*"(.*?)"\s*$/u', $q, $matches)) {
            $qb->andWhere('e.callNumber LIKE :q');
            $qb->setParameter('q', "%${matches[1]}%");
        } else {
            $qb->andWhere('MATCH(e.callNumber, e.description) AGAINST (:q BOOLEAN) > 0.1');
            $qb->setParameter('q', $q);
        }
        $qb->orderBy('e.callNumber', 'ASC');

        return $qb->getQuery();
    }
}
