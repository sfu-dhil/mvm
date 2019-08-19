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
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $untitled;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $bibliography;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $firstLineIndex;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $digitized;

    /**
     * @var boolean
     * @ORM\Column(type="string", nullable=true)
     */
    private $format;

    /**
     * @var boolean
     * @ORM\Column(type="string", nullable=true)
     */
    private $size;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $filledPageCount;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $itemCount;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $poemCount;

    /**
     * @var array|string[]
     * @ORM\Column(type="array")
     */
    private $additionalGenres;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $callNumber;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $complete;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $region;

    /**
     * @var Period
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Period", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $period;

    /**
     * @var Archive
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Archive", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $archive;

    /**
     * @var Collection|PrintSource[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PrintSource", inversedBy="manuscripts")
     */
    private $printSources;

    /**
     * @var Collection|Theme[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Theme", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $themes;

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

    public function __construct() {
        parent::__construct();
        $this->complete = false;
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
        if( ! $this->untitled) {
            return $this->title;
        }
        return '[' . $this->title . ']';
    }

    /**
     * Set region.
     *
     * @param \AppBundle\Entity\Region|null $region
     *
     * @return Manuscript
     */
    public function setRegion(\AppBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return \AppBundle\Entity\Region|null
     */
    public function getRegion()
    {
        return $this->region;
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
     * Set archive.
     *
     * @param \AppBundle\Entity\Archive $archive
     *
     * @return Manuscript
     */
    public function setArchive(\AppBundle\Entity\Archive $archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive.
     *
     * @return \AppBundle\Entity\Archive
     */
    public function getArchive()
    {
        return $this->archive;
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

    /**
     * Set callNumber.
     *
     * @param string $callNumber
     *
     * @return Manuscript
     */
    public function setCallNumber($callNumber)
    {
        $this->callNumber = $callNumber;

        return $this;
    }

    /**
     * Get callNumber.
     *
     * @return string
     */
    public function getCallNumber()
    {
        return $this->callNumber;
    }

    /**
     * Set bibliography.
     *
     * @param string $bibliography
     *
     * @return Manuscript
     */
    public function setBibliography($bibliography)
    {
        $this->bibliography = $bibliography;

        return $this;
    }

    /**
     * Get bibliography.
     *
     * @return string
     */
    public function getBibliography()
    {
        return $this->bibliography;
    }

    /**
     * Set untitled.
     *
     * @param bool $untitled
     *
     * @return Manuscript
     */
    public function setUntitled($untitled)
    {
        $this->untitled = $untitled;

        return $this;
    }

    /**
     * Get untitled.
     *
     * @return bool
     */
    public function getUntitled()
    {
        return $this->untitled;
    }

    /**
     * Set firstLineIndex.
     *
     * @param bool|null $firstLineIndex
     *
     * @return Manuscript
     */
    public function setFirstLineIndex($firstLineIndex = null)
    {
        $this->firstLineIndex = $firstLineIndex;

        return $this;
    }

    /**
     * Get firstLineIndex.
     *
     * @return bool|null
     */
    public function getFirstLineIndex()
    {
        return $this->firstLineIndex;
    }

    /**
     * Set digitized.
     *
     * @param bool|null $digitized
     *
     * @return Manuscript
     */
    public function setDigitized($digitized = null)
    {
        $this->digitized = $digitized;

        return $this;
    }

    /**
     * Get digitized.
     *
     * @return bool|null
     */
    public function getDigitized()
    {
        return $this->digitized;
    }

    /**
     * Set format.
     *
     * @param string|null $format
     *
     * @return Manuscript
     */
    public function setFormat($format = null)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format.
     *
     * @return string|null
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set size.
     *
     * @param string|null $size
     *
     * @return Manuscript
     */
    public function setSize($size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return string|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set complete.
     *
     * @param bool $complete
     *
     * @return Manuscript
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete.
     *
     * @return bool
     */
    public function getComplete()
    {
        return $this->complete;
    }
}
