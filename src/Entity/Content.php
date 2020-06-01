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
 * Content.
 *
 * @ORM\Table(name="content", indexes={
 *   @ORM\Index(name="content_ft", columns={"title", "first_line", "transcription"}, flags={"fulltext"}),
 *   @ORM\Index(name="content_firstline_idx", columns={"first_line"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 */
class Content extends AbstractEntity {
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
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="App\Entity\CircaDate", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $date;

    /**
     * @var Collection|ContentContribution[]
     * @ORM\OneToMany(targetEntity="App\Entity\ContentContribution", mappedBy="content", cascade={"persist","remove"})
     */
    private $contributions;

    /**
     * @var Collection|ManuscriptContent
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptContent", mappedBy="content", cascade={"persist","remove"})
     */
    private $manuscriptContents;

    /**
     * @var Collection|Image[]
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="content")
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
    public function __toString() : string {
        return $this->firstLine;
    }

    /**
     * Add contribution.
     *
     * @param ContentContribution $contribution
     *
     * @return Content
     */
    public function addContribution(ContentContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     *
     * @param ContentContribution $contribution
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContribution(ContentContribution $contribution) {
        return $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return ContentContribution[]|Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

    /**
     * @return Person|null
     */
    public function getAuthor() {
        foreach ($this->contributions as $contribution) {
            if ($contribution->getRole()->getName() === 'author') {
                return $contribution->getPerson();
            }
        }
        return null;
    }

    /**
     * Add image.
     *
     * @param \App\Entity\Image $image
     *
     * @return Content
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
     * @return Collection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Content
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set transcription.
     *
     * @param null|string $transcription
     *
     * @return Content
     */
    public function setTranscription($transcription = null) {
        $this->transcription = $transcription;

        return $this;
    }

    /**
     * Get transcription.
     *
     * @return null|string
     */
    public function getTranscription() {
        return $this->transcription;
    }

    /**
     * Set description.
     *
     * @param null|string $description
     *
     * @return Content
     */
    public function setDescription($description = null) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return null|string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set firstLine.
     *
     * @param string $firstLine
     *
     * @return Content
     */
    public function setFirstLine($firstLine) {
        $this->firstLine = $firstLine;

        return $this;
    }

    /**
     * Get firstLine.
     *
     * @return string
     */
    public function getFirstLine() {
        return $this->firstLine;
    }

    /**
     * Add manuscriptContent.
     *
     * @param \App\Entity\ManuscriptContent $manuscriptContent
     *
     * @return Content
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
     * @return Collection
     */
    public function getManuscriptContents() {
        return $this->manuscriptContents;
    }

    /**
     * @return CircaDate|null
     */
    public function getDate() : ?CircaDate {
        return $this->date;
    }

    /**
     * Set deathDate.
     *
     * @param null|CircaDate $date
     *
     * @return Content
     * @throws \Exception
     */
    public function setDate($date = null) {
        if (is_string($date) || is_numeric($date)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($date);
            $this->date = $dateYear;
        } else {
            $this->date = $date;
        }

        return $this;
    }
}
