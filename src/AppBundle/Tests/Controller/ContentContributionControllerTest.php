<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\ContentContribution;
use AppBundle\DataFixtures\ORM\LoadContentContribution;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class ContentContributionControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadContentContribution::class
        ];
    }
    
    /**
     * @group anon
     * @group index
     */
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/content_contribution/');
        $this->assertStatusCode(302, $client);
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    /**
     * @group user
     * @group index
     */
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/content_contribution/');
        $this->assertStatusCode(200, $client);
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    /**
     * @group admin
     * @group index
     */
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/content_contribution/');
        $this->assertStatusCode(200, $client);
    }

    /**
     * @group anon
     * @group show
     */
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/content_contribution/1');
        $this->assertStatusCode(302, $client);
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    /**
     * @group user
     * @group show
     */
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/content_contribution/1');
        $this->assertStatusCode(200, $client);
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    /**
     * @group admin
     * @group show
     */
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/content_contribution/1');
        $this->assertStatusCode(200, $client);
    }


}
