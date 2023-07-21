<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\MappedSuperclass]
abstract class AbstractSource extends AbstractTerm {
    public function __construct() {
        parent::__construct();
    }
}
