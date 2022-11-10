<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
        $this->repo = self::$container->get(ManuscriptContentRepository::class);
    }
}
