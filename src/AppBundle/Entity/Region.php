<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Region
 *
 * @todo Make this GeoNames compatible.
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegionRepository")
 */
class Region extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var Collection|PrintSource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PrintSource", mappedBy="region")
     */
    private $printSources;

    /**
     * @var Collection|Manuscript[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Manuscript", mappedBy="region")
     */
    private $manuscripts;

    /**
     * @var Collection|People[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Person", mappedBy="birthRegion")
     */
    private $peopleBorn;

    /**
     * @var Collection|People[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Person", mappedBy="deathRegion")
     */
    private $peopleDied;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->peopleDied = new ArrayCollection();
        $this->peopleBorn = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Add printSource.
     *
     * @param \AppBundle\Entity\PrintSource $printSource
     *
     * @return Region
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
     * Add manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return Region
     */
    public function addManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        return $this->manuscripts->removeElement($manuscript);
    }

    /**
     * Get manuscripts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscripts()
    {
        return $this->manuscripts;
    }

    /**
     * Add peopleBorn.
     *
     * @param \AppBundle\Entity\Person $peopleBorn
     *
     * @return Region
     */
    public function addPeopleBorn(\AppBundle\Entity\Person $peopleBorn)
    {
        $this->peopleBorn[] = $peopleBorn;

        return $this;
    }

    /**
     * Remove peopleBorn.
     *
     * @param \AppBundle\Entity\Person $peopleBorn
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePeopleBorn(\AppBundle\Entity\Person $peopleBorn)
    {
        return $this->peopleBorn->removeElement($peopleBorn);
    }

    /**
     * Get peopleBorn.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeopleBorn()
    {
        return $this->peopleBorn;
    }

    /**
     * Add peopleDied.
     *
     * @param \AppBundle\Entity\Person $peopleDied
     *
     * @return Region
     */
    public function addPeopleDied(\AppBundle\Entity\Person $peopleDied)
    {
        $this->peopleDied[] = $peopleDied;

        return $this;
    }

    /**
     * Remove peopleDied.
     *
     * @param \AppBundle\Entity\Person $peopleDied
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePeopleDied(\AppBundle\Entity\Person $peopleDied)
    {
        return $this->peopleDied->removeElement($peopleDied);
    }

    /**
     * Get peopleDied.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeopleDied()
    {
        return $this->peopleDied;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Region
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sortableName.
     *
     * @param string $sortableName
     *
     * @return Region
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
}