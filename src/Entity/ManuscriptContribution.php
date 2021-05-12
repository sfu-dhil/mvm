<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ManuscriptContribution.
 *
 * @ORM\Table(name="manuscript_contribution")
 * @ORM\Entity(repositoryClass="App\Repository\ManuscriptContributionRepository")
 */
class ManuscriptContribution extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="manuscriptContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="App\Entity\ManuscriptRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Manuscript
     * @ORM\ManyToOne(targetEntity="App\Entity\Manuscript", inversedBy="manuscriptContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manuscript;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        return implode(', ', [$this->person, $this->role, $this->manuscript]);
    }

    /**
     * Set person.
     *
     * @param \App\Entity\Person $person
     *
     * @return ManuscriptContribution
     */
    public function setPerson(Person $person) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return \App\Entity\Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * Set role.
     *
     * @param \App\Entity\ManuscriptRole $role
     *
     * @return ManuscriptContribution
     */
    public function setRole(ManuscriptRole $role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \App\Entity\ManuscriptRole
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set manuscript.
     *
     * @param \App\Entity\Manuscript $manuscript
     *
     * @return ManuscriptContribution
     */
    public function setManuscript(Manuscript $manuscript) {
        $this->manuscript = $manuscript;

        return $this;
    }

    /**
     * Get manuscript.
     *
     * @return \App\Entity\Manuscript
     */
    public function getManuscript() {
        return $this->manuscript;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return ManuscriptContribution
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote() {
        return $this->note;
    }
}
