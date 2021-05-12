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
 * PrintSource.
 *
 * @ORM\Table(name="print_source")
 * @ORM\Entity(repositoryClass="App\Repository\PrintSourceRepository")
 */
class PrintSource extends AbstractTerm {
    /**
     * @var Collection|Region[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Region", inversedBy="printSources")
     */
    private $regions;

    /**
     * @var Collection|ManuscriptContent
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptContent", mappedBy="printSource")
     */
    private $manuscriptContents;

    /**
     * @var Collection|Manuscript
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="printSources")
     */
    private $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscriptContents = new ArrayCollection();
        $this->manuscripts = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }

    /**
     * Add manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return PrintSource
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
     * Add manuscriptContent.
     *
     * @param \App\Entity\ManuscriptContent $manuscriptContent
     *
     * @return PrintSource
     */
    public function addManuscriptContent(ManuscriptContent $manuscriptContent) {
        $this->manuscriptContents[] = $manuscriptContent;

        return $this;
    }

    /**
     * Remove manuscriptContent.
     *
     * @param \App\Entity\ManuscriptContent $manuscriptContent
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptContent(ManuscriptContent $manuscriptContent) {
        return $this->manuscriptContents->removeElement($manuscriptContent);
    }

    /**
     * Get manuscriptContents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptContents() {
        return $this->manuscriptContents;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegions() : Collection {
        return $this->regions;
    }

    public function setRegions($regions) : self {
        if ($regions instanceof Collection) {
            $this->regions = $regions;
        } else {
            if (is_array($regions)) {
                $this->regions = new ArrayCollection($regions);
            } else {
                $this->regions = new ArrayCollection();
            }
        }

        return $this;
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
}
