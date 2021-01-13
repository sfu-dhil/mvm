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
use Exception;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Person.
 *
 * @ORM\Table(name="person", indexes={
 *     @ORM\Index(name="person_ft", columns={"full_name", "variant_names"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;
    }

    public const MALE = 'M';

    public const FEMALE = 'F';

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $anonymous;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $fullName;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    private $variantNames;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $sortableName;

    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="App\Entity\CircaDate", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $birthDate;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="App\Entity\CircaDate", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $deathDate;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @var Collection|ContentContribution[]
     * @ORM\OneToMany(targetEntity="App\Entity\ContentContribution", mappedBy="person")
     */
    private $contentContributions;

    /**
     * @var Collection|ManuscriptContribution[]
     * @ORM\OneToMany(targetEntity="App\Entity\ManuscriptContribution", mappedBy="person")
     */
    private $manuscriptContributions;

    /**
     * @var Collection|Coterie[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Coterie", mappedBy="people")
     */
    private $coteries;

    /**
     * @var Collection|Region[]
     * @ORM\ManyToMany(targetEntity="Region", inversedBy="people")
     */
    private $regions;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->regions = new ArrayCollection();
        $this->contentContributions = new ArrayCollection();
        $this->manuscriptContributions = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        if ( ! $this->anonymous) {
            return $this->fullName;
        }

        return '[' . $this->fullName . ']';
    }

    /**
     * Set birthDate.
     *
     * @param null|\App\Entity\CircaDate $birthDate
     *
     * @throws Exception
     *
     * @return Person
     */
    public function setBirthDate($birthDate = null) {
        if (is_string($birthDate) || is_numeric($birthDate)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($birthDate);
            $this->birthDate = $dateYear;
        } else {
            $this->birthDate = $birthDate;
        }

        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return null|\App\Entity\CircaDate
     */
    public function getBirthDate() {
        return $this->birthDate;
    }

    /**
     * Set deathDate.
     *
     * @param null|\App\Entity\CircaDate $deathDate
     *
     * @throws Exception
     *
     * @return Person
     */
    public function setDeathDate($deathDate = null) {
        if (is_string($deathDate) || is_numeric($deathDate)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($deathDate);
            $this->deathDate = $dateYear;
        } else {
            $this->deathDate = $deathDate;
        }

        return $this;
    }

    /**
     * Get deathDate.
     *
     * @return null|\App\Entity\CircaDate
     */
    public function getDeathDate() {
        return $this->deathDate;
    }

    /**
     * Add contentContribution.
     *
     * @param \App\Entity\ContentContribution $contentContribution
     *
     * @return Person
     */
    public function addContentContribution(ContentContribution $contentContribution) {
        $this->contentContributions[] = $contentContribution;

        return $this;
    }

    /**
     * Remove contentContribution.
     *
     * @param \App\Entity\ContentContribution $contentContribution
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContentContribution(ContentContribution $contentContribution) {
        return $this->contentContributions->removeElement($contentContribution);
    }

    /**
     * Get contentContributions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContentContributions() {
        return $this->contentContributions;
    }

    /**
     * Add manuscriptContribution.
     *
     * @param \App\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return Person
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptContributions() {
        return $this->manuscriptContributions;
    }

    /**
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return Person
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * Set sortableName.
     *
     * @param string $sortableName
     *
     * @return Person
     */
    public function setSortableName($sortableName) {
        $this->sortableName = $sortableName;

        return $this;
    }

    /**
     * Get sortableName.
     *
     * @return string
     */
    public function getSortableName() {
        return $this->sortableName;
    }

    /**
     * Set variantNames.
     *
     * @param null|array $variantNames
     *
     * @return Person
     */
    public function setVariantNames($variantNames = null) {
        $this->variantNames = $variantNames;

        return $this;
    }

    /**
     * Get variantNames.
     *
     * @return null|array
     */
    public function getVariantNames() {
        return $this->variantNames;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Person
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
     * Set anonymous.
     *
     * @param bool $anonymous
     *
     * @return Person
     */
    public function setAnonymous($anonymous) {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * Get anonymous.
     *
     * @return bool
     */
    public function getAnonymous() {
        return $this->anonymous;
    }

    /**
     * Set gender.
     *
     * @param null|string $gender
     *
     * @return Person
     */
    public function setGender($gender = null) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return null|string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Add region.
     *
     * @param \App\Entity\Region $region
     *
     * @return Person
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
     * @return \Doctrine\Common\Collections\Collection
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

    public function getBiography() : ?string {
        return $this->biography;
    }

    public function setBiography(?string $biography) : self {
        $this->biography = $biography;

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
