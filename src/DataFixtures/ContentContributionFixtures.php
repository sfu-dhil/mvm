<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ContentContribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContentContributionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new ContentContribution();
            $fixture->setNote("<p>This is paragraph {$i}</p>");
            $fixture->setRole($this->getReference('contentrole.' . $i));
            $fixture->setPerson($this->getReference('person.' . $i));
            $fixture->setContent($this->getReference('content.' . $i));
            $manager->persist($fixture);
            $this->setReference('contentcontribution.' . $i, $fixture);
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            ContentRoleFixtures::class,
            PersonFixtures::class,
            ContentFixtures::class,
        ];
    }
}
