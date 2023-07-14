<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrintSourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'print_source')]
#[ORM\Entity(repositoryClass: PrintSourceRepository::class)]
class PrintSource extends AbstractTerm {
    /**
     * @var Collection|Region[]
     */
    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'printSources')]
    private Collection|array $regions;

    /**
     * @var Collection|ManuscriptContent
     */
    #[ORM\OneToMany(targetEntity: ManuscriptContent::class, mappedBy: 'printSource')]
    private Collection|array $manuscriptContents;

    /**
     * @var Collection|Manuscript
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'printSources')]
    private Collection|array $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscriptContents = new ArrayCollection();
        $this->manuscripts = new ArrayCollection();
        $this->regions = new ArrayCollection();
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

    public function getRegions() : Collection {
        return $this->regions;
    }

    public function setRegions(Collection|array $regions) : self {
        if ($regions instanceof Collection) {
            $this->regions = $regions;
        } else {
            if (is_array($regions)) {
                $this->regions = new ArrayCollection($regions);
            } else {
                $this->regions = new ArrayCollection();
            }
        }

        return $this;
    }

    public function addRegion(Region $region) : self {
        if ( ! $this->regions->contains($region)) {
            $this->regions[] = $region;
        }

        return $this;
    }

    public function removeRegion(Region $region) : self {
        $this->regions->removeElement($region);

        return $this;
    }
}
