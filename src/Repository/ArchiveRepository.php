<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Archive;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ArchiveRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArchiveRepository extends AbstractSourceRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Archive::class);
    }
}
