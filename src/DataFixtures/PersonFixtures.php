<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

/**
 * Generated by Webonaute\DoctrineFixtureGenerator.
 */
class PersonFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        $manager->getClassMetadata(Person::class)->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $item1 = new Person();
        $item1->setAnonymous(false);
        $item1->setFullName('Margery Simpson');
        $item1->setSortableName('simpson margery');
        $item1->setCreated(new \DateTime('2019-07-29 22:32:27'));
        $item1->setUpdated(new \DateTime('2019-07-29 22:32:27'));
        $this->addReference('_reference_Person1', $item1);
        $manager->persist($item1);

        $item2 = new Person();
        $item2->setAnonymous(false);
        $item2->setFullName('William Wordsworth');
        $item2->setSortableName('wordsworth william');
        $item2->setCreated(new \DateTime('2019-07-29 22:34:05'));
        $item2->setUpdated(new \DateTime('2019-07-29 22:34:05'));
        $this->addReference('_reference_Person2', $item2);
        $manager->persist($item2);

        $manager->flush();
    }
}