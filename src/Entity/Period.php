<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Period.
 *
 * @ORM\Table(name="period")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodRepository")
 */
class Period extends AbstractTerm {
    /**
     * @var Collection|Manuscript[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="periods")
     */
    private $manuscripts;

    /**
     * @var Collection|Coterie[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Coterie", mappedBy="periods")
     */
    private $coteries;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    /**
     * Add manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return Period
     */
    public function addManuscript(Manuscript $manuscript) {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscript(Manuscript $manuscript) {
        return $this->manuscripts->removeElement($manuscript);
    }

    /**
     * Get manuscripts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscripts() {
        return $this->manuscripts;
    }

    /**
     * @return Collection|Period[]
     */
    public function getCoteries() : Collection {
        return $this->coteries;
    }

    public function addCotery(self $cotery) : self {
        if ( ! $this->coteries->contains($cotery)) {
            $this->coteries[] = $cotery;
            $cotery->addPeriod($this);
        }

        return $this;
    }

    public function removeCotery(self $cotery) : self {
        if ($this->coteries->removeElement($cotery)) {
            $cotery->removePeriod($this);
        }

        return $this;
    }
}
