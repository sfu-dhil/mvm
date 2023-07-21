<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'content')]
#[ORM\Index(name: 'content_ft', columns: ['first_line', 'transcription', 'description'], flags: ['fulltext'])]
#[ORM\Index(name: 'content_firstline_idx', columns: ['first_line'])]
#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[ORM\Index(name: 'content_ft', columns: ['first_line', 'transcription', 'description'], flags: ['fulltext'])]
#[ORM\Index(name: 'content_firstline_idx', columns: ['first_line'])]
class Content extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $firstLine;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $transcription;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\OneToOne(targetEntity: CircaDate::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?CircaDate $date = null;

    /**
     * @var Collection|ContentContribution[]
     */
    #[ORM\OneToMany(targetEntity: ContentContribution::class, mappedBy: 'content', cascade: ['persist', 'remove'])]
    private Collection|array $contributions;

    /**
     * @var Collection|ManuscriptContent
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContent::class, mappedBy: 'content', cascade: ['persist', 'remove'])]
    private Collection|array $manuscriptContents;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->contributions = new ArrayCollection();
        $this->manuscriptContents = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->firstLine;
    }

    public function addContribution(ContentContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ContentContribution $contribution) : bool {
        return $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }

    public function getAuthor() : ?Person {
        foreach ($this->contributions as $contribution) {
            if ('author' === $contribution->getRole()->getName()) {
                return $contribution->getPerson();
            }
        }

        return null;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTranscription(?string $transcription = null) : self {
        $this->transcription = $transcription;

        return $this;
    }

    public function getTranscription() : ?string {
        return $this->transcription;
    }

    public function setDescription(?string $description = null) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setFirstLine(string $firstLine) : self {
        $this->firstLine = $firstLine;

        return $this;
    }

    public function getFirstLine() : string {
        return $this->firstLine;
    }

    public function addManuscriptContent(ManuscriptContent $manuscriptContent) : self {
        $this->manuscriptContents[] = $manuscriptContent;

        return $this;
    }

    public function removeManuscriptContent(ManuscriptContent $manuscriptContent) : bool {
        return $this->manuscriptContents->removeElement($manuscriptContent);
    }

    public function getManuscriptContents() : Collection {
        return $this->manuscriptContents;
    }

    public function getDate() : ?CircaDate {
        return $this->date;
    }

    public function setDate(mixed $date = null) : self {
        if (is_string($date) || is_numeric($date)) {
            $dateYear = new CircaDate();
            $dateYear->setValue($date);
            $this->date = $dateYear;
        } else {
            $this->date = $date;
        }

        return $this;
    }
}
