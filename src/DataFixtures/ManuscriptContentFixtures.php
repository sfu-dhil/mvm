<?php

declare(strict_types=1);

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

    public function getDependencies() {
        return [
            ContentFixtures::class,
            ManuscriptFixtures::class,
            PrintSourceFixtures::class,
        ];
    }
}
