<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Theme.
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="App\Repository\ThemeRepository")
 */
class Theme extends AbstractTerm {
    /**
     * @var Collection|Manuscript[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="majorThemes")
     */
    private $majorManuscripts;

    /**
     * @var Collection|Manuscript[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="otherThemes")
     */
    private $otherManuscripts;

    public function __construct() {
        parent::__construct();
        $this->majorManuscripts = new ArrayCollection();
        $this->otherManuscripts = new ArrayCollection();
    }

    /**
     * @return Collection<int, Manuscript>
     */
    public function getMajorManuscripts() : Collection {
        return $this->majorManuscripts;
    }

    public function addMajorManuscript(Manuscript $majorManuscript) : self {
        if ( ! $this->majorManuscripts->contains($majorManuscript)) {
            $this->majorManuscripts[] = $majorManuscript;
            $majorManuscript->addMajorTheme($this);
        }

        return $this;
    }

    public function removeMajorManuscript(Manuscript $majorManuscript) : self {
        if ($this->majorManuscripts->removeElement($majorManuscript)) {
            $majorManuscript->removeMajorTheme($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Manuscript>
     */
    public function getOtherManuscripts() : Collection {
        return $this->otherManuscripts;
    }

    public function addOtherManuscript(Manuscript $otherManuscript) : self {
        if ( ! $this->otherManuscripts->contains($otherManuscript)) {
            $this->otherManuscripts[] = $otherManuscript;
            $otherManuscript->addOtherTheme($this);
        }

        return $this;
    }

    public function removeOtherManuscript(Manuscript $otherManuscript) : self {
        if ($this->otherManuscripts->removeElement($otherManuscript)) {
            $otherManuscript->removeOtherTheme($this);
        }

        return $this;
    }
}
