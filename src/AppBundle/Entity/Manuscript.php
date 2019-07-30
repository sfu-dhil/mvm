<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Manuscript
 *
 * @ORM\Table(name="manuscript", indexes={
 *  @ORM\Index(name="manuscript_ft", columns={"title", "description"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ManuscriptRepository")
 */
class Manuscript extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $blankPageCount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $filledPageCount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $itemCount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $poemCount;

    /**
     * @var array|string[]
     * @ORM\Column(type="array")
     */
    private $additionalGenres;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place", inversedBy="manuscripts")
     */
    private $place;

    /**
     * @var Period
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Period", inversedBy="manuscripts")
     */
    private $period;

    /**
     * @var ArchiveSource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ArchiveSource", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $archiveSource;

    /**
     * @var Collection|Content[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Content", mappedBy="manuscript")
     */
    private $contents;

    /**
     * @var Collection|ManuscriptContribution[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptContribution", mappedBy="manuscript")
     */
    private $manuscriptContributions;

    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptFeature", mappedBy="manuscript")
     */
    private $manuscriptFeatures;

    /**
     * @var Collection|PrintSource[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PrintSource", inversedBy="manuscripts")
     */
    private $printSources;

    /**
     * @var Collection|Theme[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Theme", inversedBy="manuscripts")
     */
    private $themes;

    public function __construct() {
        parent::__construct();
        $this->manuscriptContributions = new ArrayCollection();
        $this->manuscriptFeatures = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->themes = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return $this->title;
    }

    /**
     * Set place.
     *
     * @param \AppBundle\Entity\Place|null $place
     *
     * @return Manuscript
     */
    public function setPlace(\AppBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return \AppBundle\Entity\Place|null
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set period.
     *
     * @param \AppBundle\Entity\Period|null $period
     *
     * @return Manuscript
     */
    public function setPeriod(\AppBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period.
     *
     * @return \AppBundle\Entity\Period|null
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set archiveSource.
     *
     * @param \AppBundle\Entity\ArchiveSource $archiveSource
     *
     * @return Manuscript
     */
    public function setArchiveSource(\AppBundle\Entity\ArchiveSource $archiveSource)
    {
        $this->archiveSource = $archiveSource;

        return $this;
    }

    /**
     * Get archiveSource.
     *
     * @return \AppBundle\Entity\ArchiveSource
     */
    public function getArchiveSource()
    {
        return $this->archiveSource;
    }

    /**
     * Add content.
     *
     * @param \AppBundle\Entity\content $content
     *
     * @return Manuscript
     */
    public function addContent(\AppBundle\Entity\content $content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Remove content.
     *
     * @param \AppBundle\Entity\content $content
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContent(\AppBundle\Entity\content $content)
    {
        return $this->contents->removeElement($content);
    }

    /**
     * Get contents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add manuscriptContribution.
     *
     * @param \AppBundle\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return Manuscript
     */
    public function addManuscriptContribution(\AppBundle\Entity\ManuscriptContribution $manuscriptContribution)
    {
        $this->manuscriptContributions[] = $manuscriptContribution;

        return $this;
    }

    /**
     * Remove manuscriptContribution.
     *
     * @param \AppBundle\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptContribution(\AppBundle\Entity\ManuscriptContribution $manuscriptContribution)
    {
        return $this->manuscriptContributions->removeElement($manuscriptContribution);
    }

    /**
     * Get manuscriptContributions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptContributions()
    {
        return $this->manuscriptContributions;
    }

    /**
     * Add manuscriptFeature.
     *
     * @param \AppBundle\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return Manuscript
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

    /**
     * Add printSource.
     *
     * @param \AppBundle\Entity\PrintSource $printSource
     *
     * @return Manuscript
     */
    public function addPrintSource(\AppBundle\Entity\PrintSource $printSource)
    {
        $this->printSources[] = $printSource;

        return $this;
    }

    /**
     * Remove printSource.
     *
     * @param \AppBundle\Entity\PrintSource $printSource
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrintSource(\AppBundle\Entity\PrintSource $printSource)
    {
        return $this->printSources->removeElement($printSource);
    }

    /**
     * Get printSources.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrintSources()
    {
        return $this->printSources;
    }

    /**
     * Add theme.
     *
     * @param \AppBundle\Entity\Theme $theme
     *
     * @return Manuscript
     */
    public function addTheme(\AppBundle\Entity\Theme $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme.
     *
     * @param \AppBundle\Entity\Theme $theme
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTheme(\AppBundle\Entity\Theme $theme)
    {
        return $this->themes->removeElement($theme);
    }

    /**
     * Get themes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Manuscript
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
     * Set description.
     *
     * @param string $description
     *
     * @return Manuscript
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set blankPageCount.
     *
     * @param int $blankPageCount
     *
     * @return Manuscript
     */
    public function setBlankPageCount($blankPageCount)
    {
        $this->blankPageCount = $blankPageCount;

        return $this;
    }

    /**
     * Get blankPageCount.
     *
     * @return int
     */
    public function getBlankPageCount()
    {
        return $this->blankPageCount;
    }

    /**
     * Set filledPageCount.
     *
     * @param int $filledPageCount
     *
     * @return Manuscript
     */
    public function setFilledPageCount($filledPageCount)
    {
        $this->filledPageCount = $filledPageCount;

        return $this;
    }

    /**
     * Get filledPageCount.
     *
     * @return int
     */
    public function getFilledPageCount()
    {
        return $this->filledPageCount;
    }

    /**
     * Set itemCount.
     *
     * @param int $itemCount
     *
     * @return Manuscript
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    /**
     * Get itemCount.
     *
     * @return int
     */
    public function getItemCount()
    {
        return $this->itemCount;
    }

    /**
     * Set poemCount.
     *
     * @param int $poemCount
     *
     * @return Manuscript
     */
    public function setPoemCount($poemCount)
    {
        $this->poemCount = $poemCount;

        return $this;
    }

    /**
     * Get poemCount.
     *
     * @return int
     */
    public function getPoemCount()
    {
        return $this->poemCount;
    }

    /**
     * Set additionalGenres.
     *
     * @param array $additionalGenres
     *
     * @return Manuscript
     */
    public function setAdditionalGenres($additionalGenres)
    {
        $this->additionalGenres = $additionalGenres;

        return $this;
    }

    /**
     * Get additionalGenres.
     *
     * @return array
     */
    public function getAdditionalGenres()
    {
        return $this->additionalGenres;
    }
}
