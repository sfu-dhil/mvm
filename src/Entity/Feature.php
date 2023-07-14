<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FeatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'feature')]
#[ORM\Entity(repositoryClass: FeatureRepository::class)]
class Feature extends AbstractTerm {
    /**
     * @var Collection|ManuscriptFeature[]
     */
    #[ORM\OneToMany(targetEntity: ManuscriptFeature::class, mappedBy: 'feature', cascade: ['remove'])]
    private Collection|array $manuscriptFeatures;

    public function __construct() {
        parent::__construct();
        $this->manuscriptFeatures = new ArrayCollection();
    }

    public function addManuscriptFeature(ManuscriptFeature $manuscriptFeature) : self {
        $this->manuscriptFeatures[] = $manuscriptFeature;

        return $this;
    }

    public function removeManuscriptFeature(ManuscriptFeature $manuscriptFeature) : bool {
        return $this->manuscriptFeatures->removeElement($manuscriptFeature);
    }

    public function getManuscriptFeatures() : Collection {
        return $this->manuscriptFeatures;
    }
}
