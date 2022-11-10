<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
        $this->repo = self::$container->get(ContentContributionRepository::class);
    }
}
