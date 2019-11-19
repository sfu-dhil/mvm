<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Feature.
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature extends AbstractTerm {
    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="feature", cascade={"remove"})
     */
    private $images;

    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptFeature", mappedBy="feature", cascade={"remove"})
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
     * @param \AppBundle\Entity\Image $image
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
     * @param \AppBundle\Entity\Image $image
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
     * @param \AppBundle\Entity\ManuscriptFeature $manuscriptFeature
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
     * @param \AppBundle\Entity\ManuscriptFeature $manuscriptFeature
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
