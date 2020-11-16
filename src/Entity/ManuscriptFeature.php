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
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ManuscriptFeature.
 *
 * @ORM\Table(name="manuscript_feature")
 * @ORM\Entity(repositoryClass="App\Repository\ManuscriptFeatureRepository")
 */
class ManuscriptFeature extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $note;

    /**
     * @var Feature
     * @ORM\ManyToOne(targetEntity="App\Entity\Feature", inversedBy="manuscriptFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feature;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="App\Entity\Manuscript", inversedBy="manuscriptFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        return implode(', ', [$this->manuscript, $this->feature]);
    }

    /**
     * Set feature.
     *
     * @param \App\Entity\Feature $feature
     *
     * @return ManuscriptFeature
     */
    public function setFeature(Feature $feature) {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature.
     *
     * @return \App\Entity\Feature
     */
    public function getFeature() {
        return $this->feature;
    }

    /**
     * Set manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return ManuscriptFeature
     */
    public function setManuscript(Manuscript $manuscript) {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \App\Entity\Manuscript
     */
    public function getManuscript() {
        return $this->manuscript;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return ManuscriptFeature
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote() {
        return $this->note;
    }
}
