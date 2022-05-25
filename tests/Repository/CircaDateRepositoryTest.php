<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\CircaDateRepository;
use Nines\UtilBundle\TestCase\ServiceTestCase;

class CircaDateRepositoryTest extends ServiceTestCase {
    private const TYPEAHEAD_QUERY = 'circaDate';

    private CircaDateRepository $repo;

    public function testSetUp() : void {
        $this->assertInstanceOf(CircaDateRepository::class, $this->repo);
    }

    protected function setUp() : void {
        parent::setUp();
        $this->repo = self::$container->get(CircaDateRepository::class);
    }
}
