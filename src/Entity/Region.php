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
 * Region.
 *
 * @todo Make this GeoNames compatible.
 *
 * @ORM\Table(name="region", indexes={
 *     @ORM\Index(name="region_name_idx", columns={"name"}),
 *     @ORM\Index(name="region_ft", columns={"name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var Collection|PrintSource[]
     * @ORM\OneToMany(targetEntity="App\Entity\PrintSource", mappedBy="region")
     */
    private $printSources;

    /**
     * @var Collection|Manuscript[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Manuscript", mappedBy="regions")
     */
    private $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        return $this->name;
    }

    /**
     * Add printSource.
     *
     * @param \App\Entity\PrintSource $printSource
     *
     * @return Region
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrintSources() {
        return $this->printSources;
    }

    /**
     * Add manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return Region
     */
    public function addManuscript(Manuscript $manuscript) {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscript(Manuscript $manuscript) {
        return $this->manuscripts->removeElement($manuscript);
    }

    /**
     * Get manuscripts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscripts() {
        return $this->manuscripts;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Region
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add person.
     *
     * @param \App\Entity\Person $person
     *
     * @return Region
     */
    public function addPerson(Person $person) {
        $this->people[] = $person;

        return $this;
    }

    /**
     * Remove person.
     *
     * @param \App\Entity\Person $person
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePerson(Person $person) {
        return $this->people->removeElement($person);
    }

    /**
     * Get people.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople() {
        return $this->people;
    }
}
