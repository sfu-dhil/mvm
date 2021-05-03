<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="themes")
     */
    private $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
    }

    /**
     * Add manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return Theme
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
}
