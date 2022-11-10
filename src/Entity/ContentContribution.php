<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ContentContribution.
 *
 * @ORM\Table(name="content_contribution")
 * @ORM\Entity(repositoryClass="App\Repository\ContentContributionRepository")
 */
class ContentContribution extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @var ContentRole
     * @ORM\ManyToOne(targetEntity="App\Entity\ContentRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="contentContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="App\Entity\Content", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     */
    public function __toString() : string {
        return implode(', ', [$this->person, $this->role, $this->content]);
    }

    /**
     * Set role.
     *
     * @param \App\Entity\ContentRole $role
     *
     * @return ContentContribution
     */
    public function setRole(ContentRole $role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \App\Entity\ContentRole
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set person.
     *
     * @param \App\Entity\Person $person
     *
     * @return ContentContribution
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
     * Set content.
     *
     * @param \App\Entity\Content $content
     *
     * @return ContentContribution
     */
    public function setContent(Content $content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return \App\Entity\Content
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return ContentContribution
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
