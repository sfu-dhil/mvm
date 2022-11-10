<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
        $this->repo = self::$container->get(ManuscriptContributionRepository::class);
    }
}
