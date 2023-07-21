<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ManuscriptContentRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'manuscript_content')]
#[ORM\Entity(repositoryClass: ManuscriptContentRepository::class)]
class ManuscriptContent extends AbstractEntity {
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $context;

    #[ORM\ManyToOne(targetEntity: Content::class, inversedBy: 'manuscriptContents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Content $content = null;

    #[ORM\ManyToOne(targetEntity: Manuscript::class, inversedBy: 'manuscriptContents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manuscript $manuscript = null;

    #[ORM\ManyToOne(targetEntity: PrintSource::class, inversedBy: 'manuscriptContents')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?PrintSource $printSource = null;

    public function __construct() {
        parent::__construct();
    }

    public function __toString() : string {
        return implode(', ', [$this->content, $this->manuscript]);
    }

    public function setContext(?string $context = null) : self {
        $this->context = $context;

        return $this;
    }

    public function getContext() : ?string {
        return $this->context;
    }

    public function setContent(Content $content) : self {
        $this->content = $content;

        return $this;
    }

    public function getContent() : Content {
        return $this->content;
    }

    public function setManuscript(Manuscript $manuscript) : self {
        $this->manuscript = $manuscript;

        return $this;
    }

    public function getManuscript() : Manuscript {
        return $this->manuscript;
    }

    public function setPrintSource(?PrintSource $printSource) : self {
        $this->printSource = $printSource;

        return $this;
    }

    public function getPrintSource() : ?PrintSource {
        return $this->printSource;
    }
}
