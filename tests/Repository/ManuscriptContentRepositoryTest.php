<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ManuscriptContentRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ManuscriptContentRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'manuscriptContent';

    private ManuscriptContentRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ManuscriptContentRepository::class, $this->repo);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::getContainer()->get(ManuscriptContentRepository::class);
    }
}
