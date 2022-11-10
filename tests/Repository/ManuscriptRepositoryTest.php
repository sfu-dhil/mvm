<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
        $this->assertCount(4, $query->getQuery()->execute());
    }

    public function testQuotedSearchQuery() : void {
        $query = $this->repo->searchQuery('"callnumber"');
        $this->assertCount(4, $query->getQuery()->execute());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::$container->get(ManuscriptRepository::class);
    }
}
