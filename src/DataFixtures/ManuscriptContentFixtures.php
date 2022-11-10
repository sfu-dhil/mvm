<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ManuscriptContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ManuscriptContentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new ManuscriptContent();
            $fixture->setContext("<p>This is paragraph {$i}</p>");
            $fixture->setContent($this->getReference('content.' . $i));
            $fixture->setManuscript($this->getReference('manuscript.' . $i));
            $fixture->setPrintsource($this->getReference('printsource.' . $i));
            $manager->persist($fixture);
            $this->setReference('manuscriptcontent.' . $i, $fixture);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            ContentFixtures::class,
            ManuscriptFixtures::class,
            PrintSourceFixtures::class,
        ];
    }
}
