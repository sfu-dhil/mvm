<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ManuscriptContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'manuscript_contribution')]
#[ORM\Entity(repositoryClass: ManuscriptContributionRepository::class)]
class ManuscriptContribution extends AbstractEntity {
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'manuscriptContributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(targetEntity: ManuscriptRole::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ManuscriptRole $role = null;

    #[ORM\ManyToOne(targetEntity: Manuscript::class, inversedBy: 'manuscriptContributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manuscript $manuscript = null;

    public function __construct() {
        parent::__construct();
    }

    public function __toString() : string {
        return implode(', ', [$this->person, $this->role, $this->manuscript]);
    }

    public function setPerson(Person $person) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : Person {
        return $this->person;
    }

    public function setRole(ManuscriptRole $role) : self {
        $this->role = $role;

        return $this;
    }

    public function getRole() : ManuscriptRole {
        return $this->role;
    }

    public function setManuscript(Manuscript $manuscript) : self {
        $this->manuscript = $manuscript;

        return $this;
    }

    public function getManuscript() : Manuscript {
        return $this->manuscript;
    }

    public function setNote(?string $note) : self {
        $this->note = $note;

        return $this;
    }

    public function getNote() : ?string {
        return $this->note;
    }
}
