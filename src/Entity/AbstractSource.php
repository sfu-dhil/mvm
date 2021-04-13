<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * AbstractSource.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractSource extends AbstractTerm
{
    public function __construct() {
        parent::__construct();
    }
}
