<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
        $qb->innerJoin('coterie.regions', 'region');
        $qb->where('MATCH(coterie.label, coterie.description) AGAINST (:q BOOLEAN) > 0.0');
        $qb->orWhere('MATCH(region.name) AGAINST (:q BOOLEAN) > 0.0');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
