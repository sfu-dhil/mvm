<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * AbstractSource
 *
 * @ORM\Table(name="abstract_source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AbstractSourceRepository")
 * @ORM\MappedSuperclass()
 */
class AbstractSource extends AbstractTerm
{
}
