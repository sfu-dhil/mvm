<?php

declare(strict_types=1);

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

    public function getDependencies() {
        return [
            FeatureFixtures::class,
            ManuscriptFixtures::class,
        ];
    }
}
