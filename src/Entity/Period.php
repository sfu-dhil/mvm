<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PeriodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'period')]
#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period extends AbstractTerm {
    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'periods')]
    private Collection|array $manuscripts;

    /**
     * @var Collection|Coterie[]
     */
    #[ORM\ManyToMany(targetEntity: Coterie::class, mappedBy: 'periods')]
    private Collection|array $coteries;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    public function addManuscript(Manuscript $manuscript) : self {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    public function removeManuscript(Manuscript $manuscript) : bool {
        return $this->manuscripts->removeElement($manuscript);
    }

    public function getManuscripts() : Collection {
        return $this->manuscripts;
    }

    public function getCoteries() : Collection {
        return $this->coteries;
    }

    public function addCotery(Coterie $cotery) : self {
        if ( ! $this->coteries->contains($cotery)) {
            $this->coteries[] = $cotery;
            $cotery->addPeriod($this);
        }

        return $this;
    }

    public function removeCotery(Coterie $cotery) : self {
        if ($this->coteries->removeElement($cotery)) {
            $cotery->removePeriod($this);
        }

        return $this;
    }
}
