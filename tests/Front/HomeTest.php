<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class HomeTest extends WebTestCase
{
    public function testHome(): void
    {
        // je crée mon client HTTP
        $client = static::createClient();
        // je lance une requête sur '/' en GET
        $crawler = $client->request('GET', '/');
        //dump($crawler);

        // je vérifie que je reçois un HTTP 200
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');
    }
}