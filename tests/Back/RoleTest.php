<?php

namespace App\Tests\Back;

use Throwable;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\CurieWebTestCase;

class RoleTest extends CurieWebTestCase
{
    /**
     * Je teste qu'un utilisateur anonyme se fait rediriger vers la page login si il tente de voir le backoffice
     */
    public function testRedirectionLogin(): void
    {
        // je crée mon client HTTP
        $client = static::createClient();

        // pas besoin de se logger on est en anomnyme
        // je lance une requête sur '/' en GET
        $crawler = $client->request('GET', '/back/movie');

        // dump($crawler);
        // je vérifie que je reçois un HTTP 302 (HTTP_FOUND) et que je suis redirigé
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);

    }



    /**
     * Je teste qu'un utilisateur avec le ROLE_USER ne peut pas aller dans le backoffice
     */
    public function testRoleUserNotAllowedBackOffice(): void
    {
        // je crée mon client HTTP
        /** @var KernelBrowser $client */
        $client = static::createClient();

        // TODO : un utilisateur avec le ROLE_USER (user@user.com)
        // pour obtenir un utilisateur, je dois demander à Doctrine
        // il me faut le service UserRepository
        /** @var UserRepository $UserRepository */
        $UserRepository = static::getContainer()->get(UserRepository::class);
        // je vais chercher un utilisateur par son email
        $user = $UserRepository->findOneBy(["email" => "groot@root.com"]);
        //dump($user);


        // TODO : se connecter avec cet utilisateur
        // mon client HTTP, est super sympa, il suffit de lui fournir un objet utilisateur
        // Comme il n'y a pas de rendu HTML, le formulaire de connexion serait complexe à remplir.
        // le client va utiliser les "dessous" de symfony pour nous connecter
        $client->loginUser($user);

        // TODO : aller sur /back/movie
        /** @var Crawler $crawler */
        $crawler = $client->request('GET', '/back/movie');
        // TODO : on doit avoir un 403 Forbidden
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }


    /**
     * fournit une liste d'URL à tester
     */
    public function getUrls()
    {
        // @link https://www.php.net/manual/fr/language.generators.syntax.php
        // je fait un return, puis je me met en PAUSE
        yield ['/back/movie/'];
        // La prochaine foisque l'on m'appelle, je reprend après la PAUSE
        // donc ici pour le 2eme appel
        //yield ['/back/user/'];
        // donc ici pour le 3eme appel
        yield ['/back/season/'];
        // donc ici pour le 4eme appel, on termine la function
        //? puisque on ne retourne rien, PHPUnit ne relance pas la function avec @dataProvider
    }

    /**
     * Je teste qu'un utilisateur avec le ROLE_MANAGER peut aller dans le backoffice
     * 
     * @dataProvider getUrls
     */
    public function testRoleManagerAllowedBackOffice($url): void
    {
        // je crée mon client HTTP
        /** @var KernelBrowser $client */
        $client = static::createClient();
        /** @var UserRepository $UserRepository */
        $UserRepository = static::getContainer()->get(UserRepository::class);
        $user = $UserRepository->findOneBy(["email" => "broot@broot.com"]);

        $client->loginUser($user);

        /** @var Crawler $crawler */
        $crawler = $client->request('GET', $url);
        // 200 : HTTP_OK
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }



}

