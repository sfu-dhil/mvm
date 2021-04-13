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
 * Feature.
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity(repositoryClass="App\Repository\FeatureRepository")
 */
class Feature extends AbstractTerm
{
    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptFeature", mappedBy="feature", cascade={"remove"})
     */
    private $manuscriptFeatures;

    public function __construct() {
        parent::__construct();
        $this->manuscriptFeatures = new ArrayCollection();
    }

    /**
     * Add manuscriptFeature.
     *
     * @param \App\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return Feature
     */
    public function addManuscriptFeature(ManuscriptFeature $manuscriptFeature) {
        $this->manuscriptFeatures[] = $manuscriptFeature;

        return $this;
    }

    /**
     * Remove manuscriptFeature.
     *
     * @param \App\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptFeature(ManuscriptFeature $manuscriptFeature) {
        return $this->manuscriptFeatures->removeElement($manuscriptFeature);
    }

    /**
     * Get manuscriptFeatures.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptFeatures() {
        return $this->manuscriptFeatures;
    }
}
