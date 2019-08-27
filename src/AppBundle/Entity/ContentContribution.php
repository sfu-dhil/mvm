<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ContentContribution
 *
 * @ORM\Table(name="content_contribution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentContributionRepository")
 */
class ContentContribution extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @var ContentRole
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContentRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person", inversedBy="contentContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var Content
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Content", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString() {
        return implode(", ", array($this->person, $this->role, $this->content));
    }

    /**
     * Set role.
     *
     * @param \AppBundle\Entity\ContentRole $role
     *
     * @return ContentContribution
     */
    public function setRole(\AppBundle\Entity\ContentRole $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \AppBundle\Entity\ContentRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set person.
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return ContentContribution
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
     * Set content.
     *
     * @param \AppBundle\Entity\Content $content
     *
     * @return ContentContribution
     */
    public function setContent(\AppBundle\Entity\Content $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return \AppBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return ContentContribution
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
