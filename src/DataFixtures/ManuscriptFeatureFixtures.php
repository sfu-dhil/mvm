<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ManuscriptFeature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ManuscriptFeatureFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new ManuscriptFeature();
            $fixture->setNote("<p>This is paragraph {$i}</p>");
            $fixture->setFeature($this->getReference('feature.' . $i));
            $fixture->setManuscript($this->getReference('manuscript.' . $i));
            $manager->persist($fixture);
            $this->setReference('manuscriptfeature.' . $i, $fixture);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            FeatureFixtures::class,
            ManuscriptFixtures::class,
        ];
    }
}
