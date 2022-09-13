<?php

namespace App\Tests;

use App\Services\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceOmdbApiTest extends KernelTestCase
{
    public function testFecthPosterOK(): void
    {
        
        // on démarre le kernel de Symfony pour avoir accès aux services (Injection de dépendance)
        $kernel = self::bootKernel();

        // cette première assertion vérifie que l'on est dans l'environnement de TEST
        // $kernel->getEnvironment() DOIT nous renvoyer 'test'
        $this->assertSame('test', $kernel->getEnvironment());
        //? VRAI ou FAUX 
        
        // je demande à symfony de me fournir mon service
        // On va utiliser les "dessous" de l'injection de dépendance
        // on demande au container de service de nous fournir un service donné via son FQCN
        // cela est strictement identique à l'injection de dépendance habituelle
        /** @var OmdbApi $omdbApi */
        $omdbApi = static::getContainer()->get(OmdbApi::class);

        // on demande le poster de totoro
        $urlPoster = $omdbApi->fetchPoster('totoro');
        // @link https://phpunit.readthedocs.io/fr/latest/assertions.html
        $this->assertSame('https://m.media-amazon.com/images/M/MV5BYzJjMTYyMjQtZDI0My00ZjE2LTkyNGYtOTllNGQxNDMyZjE0XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg', $urlPoster);

        
        // on demande le poster de tagada-tsoin-tsoin
        $urlPoster = $omdbApi->fetchPoster('tagada-tsoin-tsoin');
        // le film n'existe pas, je dois recevoir l'url par défaut
        $this->assertSame(OmdbApi::$defaultUrlPoster, $urlPoster);
        
    }
}
