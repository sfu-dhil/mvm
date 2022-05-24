<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Manuscript;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ManuscriptFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Manuscript();
            $fixture->setUntitled(0 === $i % 2);
            $fixture->setTitle('Title ' . $i);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setBibliography("<p>This is paragraph {$i}</p>");
            $fixture->setFirstLineIndex(0 === $i % 2);
            $fixture->setDigitized(0 === $i % 2);
            $fixture->setFormat('Format ' . $i);
            $fixture->setSize('Size ' . $i);
            $fixture->setFilledPageCount('FilledPageCount ' . $i);
            $fixture->setItemCount($i);
            $fixture->setPoemCount($i);
            $fixture->setAdditionalGenres(['AdditionalGenres ' . $i]);
            $fixture->setCallNumber('CallNumber ' . $i);
            $fixture->setComplete(0 === $i % 2);
            $fixture->setArchive($this->getReference('archive.' . $i));
            $manager->persist($fixture);
            $this->setReference('manuscript.' . $i, $fixture);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            ArchiveFixtures::class,
        ];
    }
}
