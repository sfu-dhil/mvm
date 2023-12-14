<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'content_contribution')]
#[ORM\Entity(repositoryClass: ContentContributionRepository::class)]
class ContentContribution extends AbstractEntity {
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note;

    #[ORM\ManyToOne(targetEntity: ContentRole::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContentRole $role = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'contentContributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(targetEntity: Content::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Content $content = null;

    public function __construct() {
        parent::__construct();
    }

    public function __toString() : string {
        return implode(', ', [$this->person, $this->role, $this->content]);
    }

    public function setRole(?ContentRole $role) : self {
        $this->role = $role;

        return $this;
    }

    public function getRole() : ?ContentRole {
        return $this->role;
    }

    public function setPerson(?Person $person) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }

    public function setContent(?Content $content) : self {
        $this->content = $content;

        return $this;
    }

    public function getContent() : ?Content {
        return $this->content;
    }

    public function setNote(?string $note) : self {
        $this->note = $note;

        return $this;
    }

    public function getNote() : ?string {
        return $this->note;
    }
}
