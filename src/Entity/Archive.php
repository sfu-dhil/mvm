<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArchiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'archive')]
#[ORM\Entity(repositoryClass: ArchiveRepository::class)]
class Archive extends AbstractSource {
    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\OneToMany(targetEntity: Manuscript::class, mappedBy: 'archive')]
    private Collection|array $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
    }

    public function addManuscript(Manuscript $manuscript) : self {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    public function removeManuscript(Manuscript $manuscript) : bool {
        return $this->manuscripts->removeElement($manuscript);
    }

    public function getManuscripts() : Collection {
        return $this->manuscripts;
    }
}
