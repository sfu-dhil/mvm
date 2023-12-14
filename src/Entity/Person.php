<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'person')]
#[ORM\Index(name: 'person_ft', columns: ['full_name', 'variant_names'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Index(name: 'person_ft', columns: ['full_name', 'variant_names'], flags: ['fulltext'])]
class Person extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;
    }

    final public const MALE = 'M';

    final public const FEMALE = 'F';

    #[ORM\Column(type: 'boolean')]
    private ?bool $anonymous;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $fullName;

    #[ORM\Column(type: 'array', nullable: true)]
    private ?array $variantNames;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $sortableName;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    private ?string $gender;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\OneToOne(targetEntity: CircaDate::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?CircaDate $birthDate = null;

    #[ORM\OneToOne(targetEntity: CircaDate::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?CircaDate $deathDate = null;

    /**
     * @var Collection|ContentContribution[]
     */
    #[ORM\OneToMany(targetEntity: ContentContribution::class, mappedBy: 'person')]
    private Collection|array $contentContributions;

    /**
     * @var Collection|ManuscriptContribution[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContribution::class, mappedBy: 'person')]
    private Collection|array $manuscriptContributions;

    /**
     * @var Collection|Coterie[]
     */
    #[ORM\ManyToMany(targetEntity: Coterie::class, mappedBy: 'people')]
    private Collection|array $coteries;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->contentContributions = new ArrayCollection();
        $this->manuscriptContributions = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    public function __toString() : string {
        if ( ! $this->anonymous) {
            return $this->fullName;
        }

        return '[' . $this->fullName . ']';
    }

    public function setBirthDate(mixed $birthDate = null) : self {
        if (is_string($birthDate) || is_numeric($birthDate)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($birthDate);
            $this->birthDate = $dateYear;
        } else {
            $this->birthDate = $birthDate;
        }

        return $this;
    }

    public function getBirthDate() : ?CircaDate {
        return $this->birthDate;
    }

    public function setDeathDate(mixed $deathDate = null) : self {
        if (is_string($deathDate) || is_numeric($deathDate)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($deathDate);
            $this->deathDate = $dateYear;
        } else {
            $this->deathDate = $deathDate;
        }

        return $this;
    }

    public function getDeathDate() : ?CircaDate {
        return $this->deathDate;
    }

    public function addContentContribution(ContentContribution $contentContribution) : self {
        $this->contentContributions[] = $contentContribution;

        return $this;
    }

    public function removeContentContribution(ContentContribution $contentContribution) : bool {
        return $this->contentContributions->removeElement($contentContribution);
    }

    public function getContentContributions() : Collection {
        return $this->contentContributions;
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

    public function setFullName(?string $fullName) : self {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFullName() : ?string {
        return $this->fullName;
    }

    public function setSortableName(?string $sortableName) : self {
        $this->sortableName = $sortableName;

        return $this;
    }

    public function getSortableName() : ?string {
        return $this->sortableName;
    }

    public function setVariantNames(?array $variantNames = null) : self {
        $this->variantNames = $variantNames;

        return $this;
    }

    public function getVariantNames() : ?array {
        return $this->variantNames;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setAnonymous(?bool $anonymous) : self {
        $this->anonymous = $anonymous;

        return $this;
    }

    public function getAnonymous() : ?bool {
        return $this->anonymous;
    }

    public function setGender(?string $gender = null) : self {
        $this->gender = $gender;

        return $this;
    }

    public function getGender() : ?string {
        return $this->gender;
    }

    public function getCoteries() : Collection {
        return $this->coteries;
    }

    public function addCoterie($coterie) : self {
        if ( ! $this->coteries->contains($coterie)) {
            $this->coteries[] = $coterie;
            $coterie->addPerson($this);
        }

        return $this;
    }

    public function removeCoterie($coterie) : self {
        if ($this->coteries->removeElement($coterie)) {
            $coterie->removePerson($this);
        }

        return $this;
    }

    public function addCotery(Coterie $cotery) : self {
        if ( ! $this->coteries->contains($cotery)) {
            $this->coteries[] = $cotery;
            $cotery->addPerson($this);
        }

        return $this;
    }

    public function removeCotery(Coterie $cotery) : self {
        if ($this->coteries->removeElement($cotery)) {
            $cotery->removePerson($this);
        }

        return $this;
    }
}
