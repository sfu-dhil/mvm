<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ManuscriptRepository;
use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'manuscript')]
#[ORM\Index(name: 'manuscript_ft', columns: ['call_number', 'description', 'format'], flags: ['fulltext'])]
#[ORM\Index(name: 'manuscript_call_idx', columns: ['call_number'])]
#[ORM\Entity(repositoryClass: ManuscriptRepository::class)]
#[ORM\Index(name: 'manuscript_ft', columns: ['call_number', 'description', 'format'], flags: ['fulltext'])]
#[ORM\Index(name: 'manuscript_call_idx', columns: ['call_number'])]
class Manuscript extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;

    }

    #[ORM\Column(type: 'boolean')]
    private ?bool $untitled;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $bibliography;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $firstLineIndex;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $digitized;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $format;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $size;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $filledPageCount;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $itemCount;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $poemCount;

    #[ORM\Column(type: 'array')]
    private ?array $additionalGenres;

    #[ORM\Column(type: 'string')]
    private ?string $callNumber;

    #[ORM\Column(type: 'boolean')]
    private ?bool $complete;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $citation = null;

    /**
     * @var Collection|Period[]
     */
    #[ORM\ManyToMany(targetEntity: Period::class, inversedBy: 'manuscripts')]
    private Collection|array $periods;

    #[ORM\ManyToOne(targetEntity: Archive::class, inversedBy: 'manuscripts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Archive $archive = null;

    /**
     * @var Collection|PrintSource[]
     */
    #[ORM\ManyToMany(targetEntity: PrintSource::class, inversedBy: 'manuscripts')]
    private Collection|array $printSources;

    /**
     * @var Collection|Theme[]
     */
    #[ORM\JoinTable(name: 'manuscript_majortheme')]
    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'majorManuscripts')]
    #[ORM\OrderBy(['label' => 'ASC'])]
    private Collection|array $majorThemes;

    /**
     * @var Collection|Theme[]
     */
    #[ORM\JoinTable(name: 'manuscript_othertheme')]
    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'otherManuscripts')]
    #[ORM\OrderBy(['label' => 'ASC'])]
    private Collection|array $otherThemes;

    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'manuscripts')]
    private Collection|array $regions;

    /**
     * @var Collection|Coterie[]
     */
    #[ORM\ManyToMany(targetEntity: Coterie::class, mappedBy: 'manuscripts')]
    private Collection|array $coteries;

    /**
     * @var Collection|ManuscriptContribution[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContribution::class, mappedBy: 'manuscript', cascade: ['persist', 'remove'])]
    private Collection|array $manuscriptContributions;

    /**
     * @var Collection|ManuscriptContent[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContent::class, mappedBy: 'manuscript', cascade: ['persist', 'remove'])]
    private Collection|array $manuscriptContents;

    /**
     * @var Collection|ManuscriptFeature[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptFeature::class, mappedBy: 'manuscript', cascade: ['persist', 'remove'])]
    private Collection|array $manuscriptFeatures;

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
        $this->majorThemes = new ArrayCollection();
        $this->otherThemes = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->callNumber;
    }

    public function setArchive(?Archive $archive) : self {
        $this->archive = $archive;

        return $this;
    }

    public function getArchive() : ?Archive {
        return $this->archive;
    }

    public function addManuscriptContribution(ManuscriptContribution $manuscriptContribution) : self {
        $this->manuscriptContributions[] = $manuscriptContribution;

        return $this;
    }

    public function removeManuscriptContribution(ManuscriptContribution $manuscriptContribution) : bool {
        return $this->manuscriptContributions->removeElement($manuscriptContribution);
    }

    public function getManuscriptContributions() : Collection {
        return $this->manuscriptContributions;
    }

    public function addManuscriptFeature(ManuscriptFeature $manuscriptFeature) : self {
        $this->manuscriptFeatures[] = $manuscriptFeature;

        return $this;
    }

    public function removeManuscriptFeature(ManuscriptFeature $manuscriptFeature) : bool {
        return $this->manuscriptFeatures->removeElement($manuscriptFeature);
    }

    public function getManuscriptFeatures(bool $sort = false) : ArrayIterator|Collection {
        if ($sort) {
            $iterator = $this->manuscriptFeatures->getIterator();
            $iterator->uasort(fn (ManuscriptFeature $a, ManuscriptFeature $b) => strcasecmp($a->getFeature()->getLabel(), $b->getFeature()->getLabel()));

            return $iterator;
        }

        return $this->manuscriptFeatures;
    }

    public function addPrintSource(PrintSource $printSource) : self {
        $this->printSources[] = $printSource;

        return $this;
    }

    public function removePrintSource(PrintSource $printSource) : bool {
        return $this->printSources->removeElement($printSource);
    }

    public function getPrintSources() : Collection {
        return $this->printSources;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setFilledPageCount(?string $filledPageCount) : self {
        $this->filledPageCount = $filledPageCount;

        return $this;
    }

    public function getFilledPageCount() : ?string {
        return $this->filledPageCount;
    }

    public function setItemCount(?int $itemCount) : self {
        $this->itemCount = $itemCount;

        return $this;
    }

    public function getItemCount() : ?int {
        return $this->itemCount;
    }

    public function setPoemCount(?int $poemCount) : self {
        $this->poemCount = $poemCount;

        return $this;
    }

    public function getPoemCount() : ?int {
        return $this->poemCount;
    }

    public function setAdditionalGenres(?array $additionalGenres) : self {
        $this->additionalGenres = $additionalGenres;

        return $this;
    }

    public function getAdditionalGenres() : ?array {
        return $this->additionalGenres;
    }

    public function setCallNumber(?string $callNumber) : self {
        $this->callNumber = $callNumber;

        return $this;
    }

    public function getCallNumber() : ?string {
        return $this->callNumber;
    }

    public function setBibliography(?string $bibliography) : self {
        $this->bibliography = $bibliography;

        return $this;
    }

    public function getBibliography() : ?string {
        return $this->bibliography;
    }

    public function setUntitled(?bool $untitled) : self {
        $this->untitled = $untitled;

        return $this;
    }

    public function getUntitled() : ?bool {
        return $this->untitled;
    }

    public function setFirstLineIndex(?bool $firstLineIndex = null) : self {
        $this->firstLineIndex = $firstLineIndex;

        return $this;
    }

    public function getFirstLineIndex() : ?bool {
        return $this->firstLineIndex;
    }

    public function setDigitized(?bool $digitized = null) : self {
        $this->digitized = $digitized;

        return $this;
    }

    public function getDigitized() : ?bool {
        return $this->digitized;
    }

    public function setFormat(?string $format = null) : self {
        $this->format = $format;

        return $this;
    }

    public function getFormat() : ?string {
        return $this->format;
    }

    public function setSize(?string $size = null) : self {
        $this->size = $size;

        return $this;
    }

    public function getSize() : ?string {
        return $this->size;
    }

    public function setComplete(?bool $complete) : self {
        $this->complete = $complete;

        return $this;
    }

    public function getComplete() : ?bool {
        return $this->complete;
    }

    public function addManuscriptContent(ManuscriptContent $manuscriptContent) : self {
        $this->manuscriptContents[] = $manuscriptContent;

        return $this;
    }

    public function removeManuscriptContent(ManuscriptContent $manuscriptContent) : bool {
        return $this->manuscriptContents->removeElement($manuscriptContent);
    }

    public function getManuscriptContents() : Collection {
        $iterator = $this->manuscriptContents->getIterator();
        $iterator->uasort(fn (ManuscriptContent $a, ManuscriptContent $b) => strcmp($a->getContent()->getFirstLine(), $b->getContent()->getFirstLine()));

        return new ArrayCollection($iterator->getArrayCopy());
    }

    public function addPeriod(Period $period) : self {
        $this->periods[] = $period;

        return $this;
    }

    public function removePeriod(Period $period) : bool {
        return $this->periods->removeElement($period);
    }

    public function getPeriods() : Collection {
        return $this->periods;
    }

    public function addRegion(Region $region) : self {
        $this->regions[] = $region;

        return $this;
    }

    public function removeRegion(Region $region) : bool {
        return $this->regions->removeElement($region);
    }

    public function getRegions() : Collection {
        return $this->regions;
    }

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

    public function getCitation() : ?string {
        return $this->citation;
    }

    public function setCitation(?string $citation) : self {
        $this->citation = $citation;

        return $this;
    }

    public function getMajorThemes() : Collection {
        return $this->majorThemes;
    }

    public function addMajorTheme(Theme $majorTheme) : self {
        if ( ! $this->majorThemes->contains($majorTheme)) {
            $this->majorThemes[] = $majorTheme;
        }

        return $this;
    }

    public function removeMajorTheme(Theme $majorTheme) : self {
        $this->majorThemes->removeElement($majorTheme);

        return $this;
    }

    public function getOtherThemes() : Collection {
        return $this->otherThemes;
    }

    public function addOtherTheme(Theme $otherTheme) : self {
        if ( ! $this->otherThemes->contains($otherTheme)) {
            $this->otherThemes[] = $otherTheme;
        }

        return $this;
    }

    public function removeOtherTheme(Theme $otherTheme) : self {
        $this->otherThemes->removeElement($otherTheme);

        return $this;
    }

    public function getPeriodYears() : array {
        $periodYears = [];
        foreach ($this->periods as $period) {
            $label = $period->getLabel();
            preg_match_all('/\d{4}/', (string) $label, $years);
            foreach ($years as $year) {
                array_push($periodYears, ...$year);
            }
        }

        return array_map('intval', $periodYears);
    }

    public function getEarliestYear() : int {
        $periodYears = $this->getPeriodYears();

        return reset($periodYears) ?: 0;
    }

    public function getLatestYear() : int {
        $periodYears = $this->getPeriodYears();

        return end($periodYears) ?: 0;
    }
}
