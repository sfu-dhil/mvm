<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\CircaDate;
use Doctrine\Persistence\ManagerRegistry;

/**
 * CircaDateRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CircaDateRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, CircaDate::class);
    }
}
