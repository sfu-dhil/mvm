<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CircaDate;
use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ContentFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $manager) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Content();
            $fixture->setFirstLine('FirstLine ' . $i);
            $fixture->setTitle('Title ' . $i);
            $fixture->setTranscription("<p>This is paragraph {$i}</p>");
            $fixture->setDescription("<p>This is paragraph {$i}</p>");

            $date = new CircaDate();
            $date->setValue((0 === $i % 2 ? 'c' : '') . "200{$i}");
            $manager->persist($date);
            $fixture->setDate($date);

            $manager->persist($fixture);
            $this->setReference('content.' . $i, $fixture);
        }
        $manager->flush();
    }
}
