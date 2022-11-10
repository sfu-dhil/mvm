<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\CircaDate;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements FixtureGroupInterface {
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
            $fixture->setGender(0 === $i % 2 ? Person::FEMALE : Person::MALE);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");

            $birthDate = new CircaDate();
            $birthDate->setValue((0 === $i % 2 ? 'c' : '') . "195{$i}");
            $manager->persist($birthDate);
            $fixture->setBirthDate($birthDate);

            $deathDate = new CircaDate();
            $deathDate->setValue((0 === $i % 2 ? 'c' : '') . "200{$i}");
            $manager->persist($deathDate);
            $fixture->setDeathDate($deathDate);

            $manager->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }
        $manager->flush();
    }
}
