<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\CoterieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * @ORM\Entity(repositoryClass=CoterieRepository::class)
 */
class Coterie extends AbstractTerm {
    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="coteries")
     */
    private $people;

    public function __construct() {
        parent::__construct();
        $this->people = new ArrayCollection();
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople() : Collection {
        return $this->people;
    }

    public function addPerson(Person $person) : self {
        if ( ! $this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    public function removePerson(Person $person) : self {
        $this->people->removeElement($person);

        return $this;
    }
}
