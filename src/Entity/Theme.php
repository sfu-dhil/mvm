<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'theme')]
#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme extends AbstractTerm {
    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'majorThemes')]
    private Collection|array $majorManuscripts;

    /**
     * @var Collection|Manuscript[]
     */
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'otherThemes')]
    private Collection|array $otherManuscripts;

    public function __construct() {
        parent::__construct();
        $this->majorManuscripts = new ArrayCollection();
        $this->otherManuscripts = new ArrayCollection();
    }

    public function getMajorManuscripts() : Collection {
        return $this->majorManuscripts;
    }

    public function addMajorManuscript(Manuscript $majorManuscript) : self {
        if ( ! $this->majorManuscripts->contains($majorManuscript)) {
            $this->majorManuscripts[] = $majorManuscript;
            $majorManuscript->addMajorTheme($this);
        }

        return $this;
    }

    public function removeMajorManuscript(Manuscript $majorManuscript) : self {
        if ($this->majorManuscripts->removeElement($majorManuscript)) {
            $majorManuscript->removeMajorTheme($this);
        }

        return $this;
    }

    public function getOtherManuscripts() : Collection {
        return $this->otherManuscripts;
    }

    public function addOtherManuscript(Manuscript $otherManuscript) : self {
        if ( ! $this->otherManuscripts->contains($otherManuscript)) {
            $this->otherManuscripts[] = $otherManuscript;
            $otherManuscript->addOtherTheme($this);
        }

        return $this;
    }

    public function removeOtherManuscript(Manuscript $otherManuscript) : self {
        if ($this->otherManuscripts->removeElement($otherManuscript)) {
            $otherManuscript->removeOtherTheme($this);
        }

        return $this;
    }
}
