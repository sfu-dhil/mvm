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
class Feature extends AbstractTerm {
    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="feature", cascade={"remove"})
     */
    private $images;

    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptFeature", mappedBy="feature", cascade={"remove"})
     */
    private $manuscriptFeatures;

    public function __construct() {
        parent::__construct();
        $this->images = new ArrayCollection();
        $this->manuscriptFeatures = new ArrayCollection();
    }

    /**
     * Add image.
     *
     * @param \App\Entity\Image $image
     *
     * @return Feature
     */
    public function addImage(Image $image) {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \App\Entity\Image $image
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(Image $image) {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
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
