<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Person();
            $fixture->setAnonymous(0 === $i % 2);
            $fixture->setFullName('FullName ' . $i);
            $fixture->setVariantNames(['VariantNames ' . $i]);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setGender($i % 2 === 0 ? Person::FEMALE : Person::MALE);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setBirthdate($this->getReference('circadate.' . $i));
            $fixture->setDeathdate($this->getReference('circadate.' . $i));
            $manager->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            CircaDateFixtures::class,
        ];
    }
}
