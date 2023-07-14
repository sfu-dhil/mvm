<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ManuscriptFeatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'manuscript_feature')]
#[ORM\Entity(repositoryClass: ManuscriptFeatureRepository::class)]
class ManuscriptFeature extends AbstractEntity {
    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $note;

    #[ORM\ManyToOne(targetEntity: Feature::class, inversedBy: 'manuscriptFeatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Feature $feature = null;

    #[ORM\ManyToOne(targetEntity: Manuscript::class, inversedBy: 'manuscriptFeatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manuscript $manuscript = null;

    public function __construct() {
        parent::__construct();
    }

    public function __toString() : string {
        return implode(', ', [$this->manuscript, $this->feature]);
    }

    public function setFeature(Feature $feature) : self {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature() : Feature {
        return $this->feature;
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
