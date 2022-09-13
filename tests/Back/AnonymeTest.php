<?php

namespace App\Tests\Back;

use App\Tests\CurieWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test des routes interdites à un user Anonyme
 */
class AnonymeTest extends CurieWebTestCase
{
    /**
     * @dataProvider getUrlsGet
     *
     * @param string $url
     */
    public function testBackOfficeGet($url): void
    {
        // je crée mon client HTTP
        /** @var KernelBrowser $client */
        $client = static::createClient();

        /** @var Crawler $crawler */
        $crawler = $client->request('GET', $url);
        // 302 : HTTP_FOUND
        //? pas 403 FORBIDDEN, car on redirige vers la page de login
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @dataProvider getUrlsPost
     *
     * @param string $url
     */
    public function testBackOfficePost($url): void
    {
        // je crée mon client HTTP
        /** @var KernelBrowser $client */
        $client = static::createClient();

        /** @var Crawler $crawler */
        $crawler = $client->request('POST', $url);
        //403 FORBIDDEN
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * Utilisée par le dataProvider
     */
    public function getUrlsGet()
    {
        yield ['/back/movie'];
    }

     /**
     * Utilisée par le dataProvider
     */
    public function getUrlsPost()
    {
        yield ['/back/movie/new'];
    }
}
