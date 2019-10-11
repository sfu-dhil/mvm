<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * PrintSource.
 *
 * @ORM\Table(name="print_source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrintSourceRepository")
 */
class PrintSource extends AbstractTerm {
    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region", inversedBy="printSources")
     */
    private $region;

    /**
     * @var Collection|ManuscriptContent
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManuscriptContent", mappedBy="printSource")
     */
    private $manuscriptContents;

    /**
     * @var Collection|Manuscript
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Manuscript", mappedBy="printSources")
     */
    private $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscriptContents = new ArrayCollection();
        $this->manuscripts = new ArrayCollection();
    }

    /**
     * Set region.
     *
     * @param null|\AppBundle\Entity\Region $region
     *
     * @return PrintSource
     */
    public function setRegion(Region $region = null) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return null|\AppBundle\Entity\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Add manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return PrintSource
     */
    public function addManuscript(Manuscript $manuscript) {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
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
     * Add manuscriptContent.
     *
     * @param \AppBundle\Entity\ManuscriptContent $manuscriptContent
     *
     * @return PrintSource
     */
    public function addManuscriptContent(ManuscriptContent $manuscriptContent) {
        $this->manuscriptContents[] = $manuscriptContent;

        return $this;
    }

    /**
     * Remove manuscriptContent.
     *
     * @param \AppBundle\Entity\ManuscriptContent $manuscriptContent
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscriptContent(ManuscriptContent $manuscriptContent) {
        return $this->manuscriptContents->removeElement($manuscriptContent);
    }

    /**
     * Get manuscriptContents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscriptContents() {
        return $this->manuscriptContents;
    }
}
