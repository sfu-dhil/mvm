<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\CoterieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * @ORM\Entity(repositoryClass=CoterieRepository::class)
 */
class Coterie extends AbstractTerm {
    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="coteries")
     */
    private $people;

    /**
     * @var Collection|Manuscript[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", inversedBy="coteries")
     */
    private $manuscripts;

    /**
     * @var Collection|Manuscript[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Region", inversedBy="coteries")
     */
    private $regions;

    /**
     * @var Collection|Period[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Period", inversedBy="coteries")
     */
    private $periods;

    public function __construct() {
        parent::__construct();
        $this->people = new ArrayCollection();
        $this->manuscripts = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->periods = new ArrayCollection();
    }

    /**
     * @return Collection|Person[]
     */
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

    /**
     * @return Collection|Manuscript[]
     */
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

    /**
     * @return Collection|Region[]
     */
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

    /**
     * @return Collection|Period[]
     */
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
