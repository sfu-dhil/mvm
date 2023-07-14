<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ManuscriptRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'manuscript_role')]
#[ORM\Entity(repositoryClass: ManuscriptRoleRepository::class)]
class ManuscriptRole extends AbstractTerm {
    /**
     * @var Collection|ManuscriptContribution[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContribution::class, mappedBy: 'role')]
    private Collection|array $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    public function addContribution(ManuscriptContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ManuscriptContribution $contribution) : bool {
        return $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }
}
