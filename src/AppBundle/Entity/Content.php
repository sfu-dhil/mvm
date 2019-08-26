<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Content
 *
 * @ORM\Table(name="content", indexes={
 *   @ORM\Index(name="content_ft", columns={"title", "first_line", "transcription"}, flags={"fulltext"}),
 *   @ORM\Index(name="content_firstline_idx", columns={"first_line"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRepository")
 */
class Content extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $firstLine;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
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
    private $description;

    /**
     * @var Collection|ContentContribution
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContentContribution", mappedBy="content")
     */
    private $contributions;

    /**
     * @var Collection|ManuscriptContent
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptContent", mappedBy="content")
     */
    private $manuscriptContents;

    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="content")
     */
    private $images;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
        $this->manuscriptContents = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return $this->firstLine;
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

    /**
     * Set firstLine.
     *
     * @param string $firstLine
     *
     * @return Content
     */
    public function setFirstLine($firstLine)
    {
        $this->firstLine = $firstLine;

        return $this;
    }

    /**
     * Get firstLine.
     *
     * @return string
     */
    public function getFirstLine()
    {
        return $this->firstLine;
    }

    /**
     * Add manuscriptContent.
     *
     * @param \AppBundle\Entity\ManuscriptContent $manuscriptContent
     *
     * @return Content
     */
    public function addManuscriptContent(\AppBundle\Entity\ManuscriptContent $manuscriptContent)
    {
        $this->manuscriptContents[] = $manuscriptContent;

        return $this;
    }

    /**
     * Remove manuscriptContent.
     *
     * @param \AppBundle\Entity\ManuscriptContent $manuscriptContent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptContent(\AppBundle\Entity\ManuscriptContent $manuscriptContent)
    {
        return $this->manuscriptContents->removeElement($manuscriptContent);
    }

    /**
     * Get manuscriptContents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptContents()
    {
        return $this->manuscriptContents;
    }
}
