<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CoterieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Entity(repositoryClass: CoterieRepository::class)]
class Coterie extends AbstractTerm {
    /**
     * @var Collection|Person[]
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'coteries')]
    private Collection|array $people;

    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, inversedBy: 'coteries')]
    private Collection|array $manuscripts;

    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'coteries')]
    private Collection|array $regions;

    /**
     * @var Collection|Period[]
     */
    #[ORM\ManyToMany(targetEntity: Period::class, inversedBy: 'coteries')]
    private Collection|array $periods;

    public function __construct() {
        parent::__construct();
        $this->people = new ArrayCollection();
        $this->manuscripts = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->periods = new ArrayCollection();
    }

    public function getPeople() : Collection {
        return $this->people;
    }

    public function addPerson(Person $person) : self {
        if ( ! $this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    public function removePerson(Person $person) : self {
        $this->people->removeElement($person);

        return $this;
    }

    public function getManuscripts() : Collection {
        return $this->manuscripts;
    }

    public function addManuscript(Manuscript $manuscript) : self {
        if ( ! $this->manuscripts->contains($manuscript)) {
            $this->manuscripts[] = $manuscript;
        }

        return $this;
    }

    public function removeManuscript(Manuscript $manuscript) : self {
        $this->manuscripts->removeElement($manuscript);

        return $this;
    }

    public function getRegions() : Collection {
        return $this->regions;
    }

    public function addRegion(Region $region) : self {
        if ( ! $this->regions->contains($region)) {
            $this->regions[] = $region;
        }

        return $this;
    }

    public function removeRegion(Region $region) : self {
        $this->regions->removeElement($region);

        return $this;
    }

    public function getPeriods() : Collection {
        return $this->periods;
    }

    public function addPeriod(Period $period) : self {
        if ( ! $this->periods->contains($period)) {
            $this->periods[] = $period;
        }

        return $this;
    }

    public function removePeriod(Period $period) : self {
        $this->periods->removeElement($period);

        return $this;
    }
}
