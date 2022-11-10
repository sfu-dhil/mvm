<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Repository;

use App\Repository\RegionRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class RegionRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'name';

    private RegionRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(RegionRepository::class, $this->repo);
    }

    public function testTypeaheadQuery() : void {
        $query = $this->repo->typeaheadQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    public function testSearchQuery() : void {
        $query = $this->repo->searchQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::$container->get(RegionRepository::class);
    }
}
