<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Manuscript.
 *
 * @ORM\Table(name="manuscript", indexes={
 *     @ORM\Index(name="manuscript_ft", columns={"call_number", "description", "format"}, flags={"fulltext"}),
 *     @ORM\Index(name="manuscript_call_idx", columns={"call_number"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ManuscriptRepository")
 */
class Manuscript extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;

    }

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $untitled;

    /**
     * @var string
     * @ORM\Column(type="string", length=512, nullable=true)
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
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $firstLineIndex;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $digitized;

    /**
     * @var bool
     * @ORM\Column(type="string", nullable=true)
     */
    private $format;

    /**
     * @var bool
     * @ORM\Column(type="string", nullable=true)
     */
    private $size;

    /**
     * @var int
     * @ORM\Column(type="string", nullable=true)
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
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $complete;

    /**
     * @var Collection|Period[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Period", inversedBy="manuscripts")
     */
    private $periods;

    /**
     * @var Archive
     * @ORM\ManyToOne(targetEntity="App\Entity\Archive", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $archive;

    /**
     * @var Collection|PrintSource[]
     * @ORM\ManyToMany(targetEntity="App\Entity\PrintSource", inversedBy="manuscripts")
     */
    private $printSources;

    /**
     * @var Collection|Theme[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Theme", inversedBy="manuscripts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $themes;

    /**
     * @var Region
     * @ORM\ManyToMany(targetEntity="App\Entity\Region", inversedBy="manuscripts")
     */
    private $regions;

    /**
     * @var Collection|Coterie[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Coterie", mappedBy="manuscripts")
     */
    private $coteries;

    /**
     * @var Collection|ManuscriptContribution[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptContribution", mappedBy="manuscript", cascade={"persist", "remove"})
     */
    private $manuscriptContributions;

    /**
     * @var Collection|ManuscriptContent[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptContent", mappedBy="manuscript", cascade={"persist", "remove"})
     */
    private $manuscriptContents;

    /**
     * @var Collection|ManuscriptFeature[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptFeature", mappedBy="manuscript", cascade={"persist", "remove"})
     */
    private $manuscriptFeatures;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->complete = false;
        $this->regions = new ArrayCollection();
        $this->periods = new ArrayCollection();
        $this->manuscriptContents = new ArrayCollection();
        $this->manuscriptContributions = new ArrayCollection();
        $this->manuscriptFeatures = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        return $this->callNumber;
    }

    /**
     * Set archive.
     *
     * @param \App\Entity\Archive $archive
     *
     * @return Manuscript
     */
    public function setArchive(Archive $archive) {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive.
     *
     * @return \App\Entity\Archive
     */
    public function getArchive() {
        return $this->archive;
    }

    /**
     * Add manuscriptContribution.
     *
     * @param \App\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return Manuscript
     */
    public function addManuscriptContribution(ManuscriptContribution $manuscriptContribution) {
        $this->manuscriptContributions[] = $manuscriptContribution;

        return $this;
    }

    /**
     * Remove manuscriptContribution.
     *
     * @param \App\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptContribution(ManuscriptContribution $manuscriptContribution) {
        return $this->manuscriptContributions->removeElement($manuscriptContribution);
    }

    /**
     * Get manuscriptContributions.
     *
     * @return Collection
     */
    public function getManuscriptContributions() {
        return $this->manuscriptContributions;
    }

    /**
     * Add manuscriptFeature.
     *
     * @param \App\Entity\ManuscriptFeature $manuscriptFeature
     *
     * @return Manuscript
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
     * @param bool $sort
     *
     * @return ArrayIterator|Collection|ManuscriptFeature[]
     */
    public function getManuscriptFeatures($sort = false) {
        if ($sort) {
            $iterator = $this->manuscriptFeatures->getIterator();
            $iterator->uasort(fn (ManuscriptFeature $a, ManuscriptFeature $b) => strcasecmp($a->getFeature()->getLabel(), $b->getFeature()->getLabel()));

            return $iterator;
        }

        return $this->manuscriptFeatures;
    }

    /**
     * Add printSource.
     *
     * @param \App\Entity\PrintSource $printSource
     *
     * @return Manuscript
     */
    public function addPrintSource(PrintSource $printSource) {
        $this->printSources[] = $printSource;

        return $this;
    }

    /**
     * Remove printSource.
     *
     * @param \App\Entity\PrintSource $printSource
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrintSource(PrintSource $printSource) {
        return $this->printSources->removeElement($printSource);
    }

    /**
     * Get printSources.
     *
     * @return Collection
     */
    public function getPrintSources() {
        return $this->printSources;
    }

    /**
     * Add theme.
     *
     * @param \App\Entity\Theme $theme
     *
     * @return Manuscript
     */
    public function addTheme(Theme $theme) {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme.
     *
     * @param \App\Entity\Theme $theme
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTheme(Theme $theme) {
        return $this->themes->removeElement($theme);
    }

    /**
     * Get themes.
     *
     * @return Collection
     */
    public function getThemes() {
        return $this->themes;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Manuscript
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
     * Set description.
     *
     * @param string $description
     *
     * @return Manuscript
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set blankPageCount.
     *
     * @param int $blankPageCount
     *
     * @return Manuscript
     */
    public function setBlankPageCount($blankPageCount) {
        $this->blankPageCount = $blankPageCount;

        return $this;
    }

    /**
     * Get blankPageCount.
     *
     * @return int
     */
    public function getBlankPageCount() {
        return $this->blankPageCount;
    }

    /**
     * Set filledPageCount.
     *
     * @param int $filledPageCount
     *
     * @return Manuscript
     */
    public function setFilledPageCount($filledPageCount) {
        $this->filledPageCount = $filledPageCount;

        return $this;
    }

    /**
     * Get filledPageCount.
     *
     * @return int
     */
    public function getFilledPageCount() {
        return $this->filledPageCount;
    }

    /**
     * Set itemCount.
     *
     * @param int $itemCount
     *
     * @return Manuscript
     */
    public function setItemCount($itemCount) {
        $this->itemCount = $itemCount;

        return $this;
    }

    /**
     * Get itemCount.
     *
     * @return int
     */
    public function getItemCount() {
        return $this->itemCount;
    }

    /**
     * Set poemCount.
     *
     * @param int $poemCount
     *
     * @return Manuscript
     */
    public function setPoemCount($poemCount) {
        $this->poemCount = $poemCount;

        return $this;
    }

    /**
     * Get poemCount.
     *
     * @return int
     */
    public function getPoemCount() {
        return $this->poemCount;
    }

    /**
     * Set additionalGenres.
     *
     * @param array $additionalGenres
     *
     * @return Manuscript
     */
    public function setAdditionalGenres($additionalGenres) {
        $this->additionalGenres = $additionalGenres;

        return $this;
    }

    /**
     * Get additionalGenres.
     *
     * @return array
     */
    public function getAdditionalGenres() {
        return $this->additionalGenres;
    }

    /**
     * Set callNumber.
     *
     * @param string $callNumber
     *
     * @return Manuscript
     */
    public function setCallNumber($callNumber) {
        $this->callNumber = $callNumber;

        return $this;
    }

    /**
     * Get callNumber.
     *
     * @return string
     */
    public function getCallNumber() {
        return $this->callNumber;
    }

    /**
     * Set bibliography.
     *
     * @param string $bibliography
     *
     * @return Manuscript
     */
    public function setBibliography($bibliography) {
        $this->bibliography = $bibliography;

        return $this;
    }

    /**
     * Get bibliography.
     *
     * @return string
     */
    public function getBibliography() {
        return $this->bibliography;
    }

    /**
     * Set untitled.
     *
     * @param bool $untitled
     *
     * @return Manuscript
     */
    public function setUntitled($untitled) {
        $this->untitled = $untitled;

        return $this;
    }

    /**
     * Get untitled.
     *
     * @return bool
     */
    public function getUntitled() {
        return $this->untitled;
    }

    /**
     * Set firstLineIndex.
     *
     * @param null|bool $firstLineIndex
     *
     * @return Manuscript
     */
    public function setFirstLineIndex($firstLineIndex = null) {
        $this->firstLineIndex = $firstLineIndex;

        return $this;
    }

    /**
     * Get firstLineIndex.
     *
     * @return null|bool
     */
    public function getFirstLineIndex() {
        return $this->firstLineIndex;
    }

    /**
     * Set digitized.
     *
     * @param null|bool $digitized
     *
     * @return Manuscript
     */
    public function setDigitized($digitized = null) {
        $this->digitized = $digitized;

        return $this;
    }

    /**
     * Get digitized.
     *
     * @return null|bool
     */
    public function getDigitized() {
        return $this->digitized;
    }

    /**
     * Set format.
     *
     * @param null|string $format
     *
     * @return Manuscript
     */
    public function setFormat($format = null) {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format.
     *
     * @return null|string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * Set size.
     *
     * @param null|string $size
     *
     * @return Manuscript
     */
    public function setSize($size = null) {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return null|string
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set complete.
     *
     * @param bool $complete
     *
     * @return Manuscript
     */
    public function setComplete($complete) {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete.
     *
     * @return bool
     */
    public function getComplete() {
        return $this->complete;
    }

    /**
     * Add manuscriptContent.
     *
     * @param \App\Entity\ManuscriptContent $manuscriptContent
     *
     * @return Manuscript
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
        $iterator = $this->manuscriptContents->getIterator();
        $iterator->uasort(fn (ManuscriptContent $a, ManuscriptContent $b) => strcmp($a->getContent()->getFirstLine(), $b->getContent()->getFirstLine()));

        return new ArrayCollection($iterator->getArrayCopy());
    }

    /**
     * Add period.
     *
     * @param \App\Entity\Period $period
     *
     * @return Manuscript
     */
    public function addPeriod(Period $period) {
        $this->periods[] = $period;

        return $this;
    }

    /**
     * Remove period.
     *
     * @param \App\Entity\Period $period
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePeriod(Period $period) {
        return $this->periods->removeElement($period);
    }

    /**
     * Get periods.
     *
     * @return Collection
     */
    public function getPeriods() {
        return $this->periods;
    }

    /**
     * Add region.
     *
     * @param \App\Entity\Region $region
     *
     * @return Manuscript
     */
    public function addRegion(Region $region) {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region.
     *
     * @param \App\Entity\Region $region
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRegion(Region $region) {
        return $this->regions->removeElement($region);
    }

    /**
     * Get regions.
     *
     * @return Collection
     */
    public function getRegions() {
        return $this->regions;
    }

    /**
     * @return Collection|Coterie[]
     */
    public function getCoteries() : Collection {
        return $this->coteries;
    }

    public function addCotery(Coterie $cotery) : self {
        if ( ! $this->coteries->contains($cotery)) {
            $this->coteries[] = $cotery;
            $cotery->addManuscript($this);
        }

        return $this;
    }

    public function removeCotery(Coterie $cotery) : self {
        if ($this->coteries->removeElement($cotery)) {
            $cotery->removeManuscript($this);
        }

        return $this;
    }
}
