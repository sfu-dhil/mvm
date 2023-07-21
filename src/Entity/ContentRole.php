<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'content_role')]
#[ORM\Entity(repositoryClass: ContentRoleRepository::class)]
class ContentRole extends AbstractTerm {
    /**
     * @var Collection|ContentContribution[]
     */
    #[ORM\OneToMany(targetEntity: ContentContribution::class, mappedBy: 'role')]
    private Collection|array $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    public function addContribution(ContentContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ContentContribution $contribution) : bool {
        return $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }
}
