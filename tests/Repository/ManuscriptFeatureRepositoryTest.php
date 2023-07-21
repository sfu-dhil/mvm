<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ManuscriptFeatureRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ManuscriptFeatureRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'label';

    private ManuscriptFeatureRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ManuscriptFeatureRepository::class, $this->repo);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::getContainer()->get(ManuscriptFeatureRepository::class);
    }
}
