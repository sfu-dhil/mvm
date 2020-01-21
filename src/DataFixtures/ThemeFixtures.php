<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

/**
 * Generated by Webonaute\DoctrineFixtureGenerator.
 */
class ThemeFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        $manager->getClassMetadata(Theme::class)->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $item1 = new Theme();
        $item1->setCreated(new \DateTime('2019-07-29 22:25:27'));
        $item1->setUpdated(new \DateTime('2019-07-29 22:25:27'));
        $item1->setName('butterflies');
        $item1->setLabel('Butterflies');
        $item1->setDescription('<p>Butterflies, leopodoptra, etc.</p>');
        $this->addReference('_reference_Theme1', $item1);
        $manager->persist($item1);

        $manager->flush();
    }
}