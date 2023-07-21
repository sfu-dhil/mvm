<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ManuscriptContributionRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ManuscriptContributionRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'manuscriptContribution';

    private ManuscriptContributionRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ManuscriptContributionRepository::class, $this->repo);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::getContainer()->get(ManuscriptContributionRepository::class);
    }
}
