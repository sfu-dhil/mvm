<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * AbstractSource
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractSource extends AbstractTerm
{
    public function __construct() {
        parent::__construct();
    }

}
