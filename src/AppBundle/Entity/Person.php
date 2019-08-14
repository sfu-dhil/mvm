<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Person
 *
 * @ORM\Table(name="person", indexes={
 *  @ORM\Index(name="person_ft", columns={"full_name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person extends AbstractEntity
{

    /**
     * @var boolean
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CircaDate", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $birthDate;

    /**
     * @var CircaDate
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CircaDate", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $deathDate;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="peopleBorn")
     */
    private $birthPlace;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="peopleDied")
     */
    private $deathPlace;

    /**
     * @var Collection|ContentContribution[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContentContribution", mappedBy="person")
     */
    private $contentContributions;

    /**
     * @var Collection|ManuscriptContribution[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptContribution", mappedBy="person")
     */
    private $manuscriptContributions;

    public function __construct() {
        parent::__construct();
        $this->contentContributions = new ArrayCollection();
        $this->manuscriptContributions = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        if( ! $this->anonymous) {
            return $this->fullName;
        }
        return '[' . $this->fullName . ']';
    }

    /**
     * Set birthDate.
     *
     * @param \AppBundle\Entity\CircaDate|null $birthDate
     *
     * @return Person
     */
    public function setBirthDate($birthDate = null)
    {
        if(is_string($birthDate) || is_numeric($birthDate)) {
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
     * @return \AppBundle\Entity\CircaDate|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set deathDate.
     *
     * @param \AppBundle\Entity\CircaDate|null $deathDate
     *
     * @return Person
     */
    public function setDeathDate($deathDate = null)
    {
        if(is_string($deathDate) || is_numeric($deathDate)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($deathDate);
            $this->birthDate = $dateYear;
        } else {
            $this->birthDate = $deathDate;
        }
    }

    /**
     * Get deathDate.
     *
     * @return \AppBundle\Entity\CircaDate|null
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * Set birthPlace.
     *
     * @param \AppBundle\Entity\Place|null $birthPlace
     *
     * @return Person
     */
    public function setBirthPlace(\AppBundle\Entity\Place $birthPlace = null)
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    /**
     * Get birthPlace.
     *
     * @return \AppBundle\Entity\Place|null
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Set deathPlace.
     *
     * @param \AppBundle\Entity\Place|null $deathPlace
     *
     * @return Person
     */
    public function setDeathPlace(\AppBundle\Entity\Place $deathPlace = null)
    {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    /**
     * Get deathPlace.
     *
     * @return \AppBundle\Entity\Place|null
     */
    public function getDeathPlace()
    {
        return $this->deathPlace;
    }

    /**
     * Add contentContribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contentContribution
     *
     * @return Person
     */
    public function addContentContribution(\AppBundle\Entity\ContentContribution $contentContribution)
    {
        $this->contentContributions[] = $contentContribution;

        return $this;
    }

    /**
     * Remove contentContribution.
     *
     * @param \AppBundle\Entity\ContentContribution $contentContribution
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContentContribution(\AppBundle\Entity\ContentContribution $contentContribution)
    {
        return $this->contentContributions->removeElement($contentContribution);
    }

    /**
     * Get contentContributions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContentContributions()
    {
        return $this->contentContributions;
    }

    /**
     * Add manuscriptContribution.
     *
     * @param \AppBundle\Entity\ManuscriptContribution $manuscriptContribution
     *
     * @return Person
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
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return Person
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set sortableName.
     *
     * @param string $sortableName
     *
     * @return Person
     */
    public function setSortableName($sortableName)
    {
        $this->sortableName = $sortableName;

        return $this;
    }

    /**
     * Get sortableName.
     *
     * @return string
     */
    public function getSortableName()
    {
        return $this->sortableName;
    }

    /**
     * Set variantNames.
     *
     * @param array|null $variantNames
     *
     * @return Person
     */
    public function setVariantNames($variantNames = null)
    {
        $this->variantNames = $variantNames;

        return $this;
    }

    /**
     * Get variantNames.
     *
     * @return array|null
     */
    public function getVariantNames()
    {
        return $this->variantNames;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Person
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
     * Set anonymous.
     *
     * @param bool $anonymous
     *
     * @return Person
     */
    public function setAnonymous($anonymous)
    {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * Get anonymous.
     *
     * @return bool
     */
    public function getAnonymous()
    {
        return $this->anonymous;
    }
}
