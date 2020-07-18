<?php

namespace App\Tests\Infra\Home\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function i_should_see_homepage(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Welcome!');
    }
}
