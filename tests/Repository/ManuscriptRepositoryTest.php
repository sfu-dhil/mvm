<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\ManuscriptRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class ManuscriptRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'callnumber';

    private ManuscriptRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(ManuscriptRepository::class, $this->repo);
    }

    public function testTypeaheadQuery() : void {
        $query = $this->repo->typeaheadQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    public function testSearchQuery() : void {
        $query = $this->repo->searchQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    public function testQuotedSearchQuery() : void {
        $query = $this->repo->searchQuery('"callnumber"');
        $this->assertCount(4, $query->execute());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::$container->get(ManuscriptRepository::class);
    }
}
