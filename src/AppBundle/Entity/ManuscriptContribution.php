<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ManuscriptContribution
 *
 * @ORM\Table(name="manuscript_contribution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ManuscriptContributionRepository")
 */
class ManuscriptContribution extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $note;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="manuscriptContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManuscriptRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manuscript", inversedBy="")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return get_class() . ' #' . $this->id;
    }

    /**
     * Set person.
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return ManuscriptContribution
     */
    public function setPerson(\AppBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set role.
     *
     * @param \AppBundle\Entity\ManuscriptRole $role
     *
     * @return ManuscriptContribution
     */
    public function setRole(\AppBundle\Entity\ManuscriptRole $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \AppBundle\Entity\ManuscriptRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return ManuscriptContribution
     */
    public function setManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \AppBundle\Entity\Manuscript
     */
    public function getManuscript()
    {
        return $this->manuscript;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return ManuscriptContribution
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
