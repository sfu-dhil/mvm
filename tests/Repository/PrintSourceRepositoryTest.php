<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Repository;

use App\Repository\PrintSourceRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class PrintSourceRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'label';

    private PrintSourceRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(PrintSourceRepository::class, $this->repo);
    }

    public function testSearchQuery() : void {
        $query = $this->repo->searchQuery(self::TYPEAHEAD_QUERY);
        $this->assertCount(4, $query->execute());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::$container->get(PrintSourceRepository::class);
    }
}
