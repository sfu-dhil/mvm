<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Feature
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeatureRepository")
 */
class Feature extends AbstractEntity
{

    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="feature")
     */
    private $images;

    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptFeature", mappedBy="feature")
     */
    private $manuscriptFeatures;

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return get_class() . ' #' . $this->id;
    }

    /**
     * Add image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Feature
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add manuscriptFeature.
     *
     * @param \AppBundle\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return Feature
     */
    public function addManuscriptFeature(\AppBundle\Entity\ManuscriptFeature $manuscriptFeature)
    {
        $this->manuscriptFeatures[] = $manuscriptFeature;

        return $this;
    }

    /**
     * Remove manuscriptFeature.
     *
     * @param \AppBundle\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptFeature(\AppBundle\Entity\ManuscriptFeature $manuscriptFeature)
    {
        return $this->manuscriptFeatures->removeElement($manuscriptFeature);
    }

    /**
     * Get manuscriptFeatures.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptFeatures()
    {
        return $this->manuscriptFeatures;
    }
}
