<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ContentContributionRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ContentContributionRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'contentContribution';

    private ContentContributionRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ContentContributionRepository::class, $this->repo);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::getContainer()->get(ContentContributionRepository::class);
    }
}
