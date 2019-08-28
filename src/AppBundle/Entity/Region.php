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
 * @ORM\Table(name="region", indexes={
 *   @ORM\Index(name="region_name_idx", columns={"name"})
 * })
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Person", mappedBy="regions")
     */
    private $people;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->people = new ArrayCollection();
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
     * Add person.
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Region
     */
    public function addPerson(\AppBundle\Entity\Person $person)
    {
        $this->people[] = $person;

        return $this;
    }

    /**
     * Remove person.
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePerson(\AppBundle\Entity\Person $person)
    {
        return $this->people->removeElement($person);
    }

    /**
     * Get people.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople()
    {
        return $this->people;
    }
}
