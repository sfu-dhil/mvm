<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ContentRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ContentRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'title';

    private ContentRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ContentRepository::class, $this->repo);
    }

    public function testTypeaheadQuery() : void {
        $query = $this->repo->typeaheadQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    public function testSearchQuery() : void {
        $query = $this->repo->searchQuery('firstline');
        $this->assertCount(4, $query->execute());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::getContainer()->get(ContentRepository::class);
    }
}
