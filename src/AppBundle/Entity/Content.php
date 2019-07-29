<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRepository")
 */
class Content extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $transcription;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $context;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manuscript", inversedBy="contents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    /**
     * @var PrintSource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PrintSource", inversedBy="contents")
     */
    private $printSource;

    /**
     * @var Collection|ContentContribution
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContentContribution", mappedBy="content")
     */
    private $contributions;

    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="content")
     */
    private $images;

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return get_class() . ' #' . $this->id;
    }

    /**
     * Set manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return Content
     */
    public function setManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \AppBundle\Entity\Manuscript
     */
    public function getManuscript()
    {
        return $this->manuscript;
    }

    /**
     * Set printSource.
     *
     * @param \AppBundle\Entity\PrintSource|null $printSource
     *
     * @return Content
     */
    public function setPrintSource(\AppBundle\Entity\PrintSource $printSource = null)
    {
        $this->printSource = $printSource;

        return $this;
    }

    /**
     * Get printSource.
     *
     * @return \AppBundle\Entity\PrintSource|null
     */
    public function getPrintSource()
    {
        return $this->printSource;
    }

    /**
     * Add contribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contribution
     *
     * @return Content
     */
    public function addContribution(\AppBundle\Entity\ContentContribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contribution
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContribution(\AppBundle\Entity\ContentContribution $contribution)
    {
        return $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }

    /**
     * Add image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Content
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
     * Set title.
     *
     * @param string $title
     *
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set transcription.
     *
     * @param string|null $transcription
     *
     * @return Content
     */
    public function setTranscription($transcription = null)
    {
        $this->transcription = $transcription;

        return $this;
    }

    /**
     * Get transcription.
     *
     * @return string|null
     */
    public function getTranscription()
    {
        return $this->transcription;
    }

    /**
     * Set context.
     *
     * @param string|null $context
     *
     * @return Content
     */
    public function setContext($context = null)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context.
     *
     * @return string|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Content
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
}
