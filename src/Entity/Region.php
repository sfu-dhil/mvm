<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @todo Make this GeoNames compatible.
 */
#[ORM\Table(name: 'region')]
#[ORM\Index(name: 'region_name_idx', columns: ['name'])]
#[ORM\Index(name: 'region_ft', columns: ['name'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Index(name: 'region_name_idx', columns: ['name'])]
#[ORM\Index(name: 'region_ft', columns: ['name'], flags: ['fulltext'])]
class Region extends AbstractEntity {
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $name;

    /**
     * @var Collection|PrintSource[]
     */
    #[ORM\ManyToMany(targetEntity: PrintSource::class, mappedBy: 'regions')]
    private Collection|array $printSources;

    /**
     * @var Collection|Coterie[]
     */
    #[ORM\ManyToMany(targetEntity: Coterie::class, mappedBy: 'regions')]
    private Collection|array $coteries;

    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'regions')]
    private Collection|array $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
        $this->printSources = new ArrayCollection();
        $this->coteries = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function addPrintSource(PrintSource $printSource) : self {
        $this->printSources[] = $printSource;

        return $this;
    }

    public function removePrintSource(PrintSource $printSource) : bool {
        return $this->printSources->removeElement($printSource);
    }

    public function getPrintSources() : Collection {
        return $this->printSources;
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

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function getCoteries() : Collection {
        return $this->coteries;
    }

    public function addCotery(Coterie $cotery) : self {
        if ( ! $this->coteries->contains($cotery)) {
            $this->coteries[] = $cotery;
            $cotery->addRegion($this);
        }

        return $this;
    }

    public function removeCotery(Coterie $cotery) : self {
        if ($this->coteries->removeElement($cotery)) {
            $cotery->removeRegion($this);
        }

        return $this;
    }
}
