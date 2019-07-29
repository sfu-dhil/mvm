<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use AppBundle\Entity\Person;

/**
 * Generated by Webonaute\DoctrineFixtureGenerator.
 *
 */
class LoadAppBundleEntityPerson extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Set loading order.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->getClassMetadata(Person::class)->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    
        $item1 = new Person();
        $item1->setFullName("Margery Simpson");
        $item1->setSortableName("simpson margery");
        $item1->setCreated(new \DateTime("2019-07-29 22:32:27"));
        $item1->setUpdated(new \DateTime("2019-07-29 22:32:27"));
        $this->addReference('_reference_AppBundleEntityPerson1', $item1);
        $manager->persist($item1);

        $item2 = new Person();
        $item2->setFullName("William Wordsworth");
        $item2->setSortableName("wordsworth william");
        $item2->setCreated(new \DateTime("2019-07-29 22:34:05"));
        $item2->setUpdated(new \DateTime("2019-07-29 22:34:05"));
        $this->addReference('_reference_AppBundleEntityPerson2', $item2);
        $manager->persist($item2);

    
        $manager->flush();
    }

}
