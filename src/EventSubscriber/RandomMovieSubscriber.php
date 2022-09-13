<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class RandomMovieSubscriber implements EventSubscriberInterface
{
    private $movieRepository, 
            $twig;
    /**
    * Constructor
    */
    public function __construct(MovieRepository $repo, Environment $twig)
    {
        $this->movieRepository = $repo;
        $this->twig = $twig;
    }
    public function onKernelController(ControllerEvent $event): void
    {
        // dd($event);
        /*
        ^ Symfony\Component\HttpKernel\Event\ControllerEvent {#1678 ▼
            -controller: array:2 [▼
                0 => App\Controller\Front\MainController {#2130 ▶}
                1 => "home"
            ]
            -kernel: Symfony\Component\HttpKernel\HttpKernel {#4870 ▶}
            -request: Symfony\Component\HttpFoundation\Request {#3 ▶}
            -requestType: 1
            -propagationStopped: false
            }
        */
       
        // TODO : d'une requete kustom : MovieRepository
        $movie = $this->movieRepository->findRandomMovie();

        // TODO : donner à twig le film au hasard
        $this->twig->addGlobal("randomMovie", $movie);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
            
            // si on s'abonne a cet évenement, ça ne fonctionne pas
            // car le rendu de twig est demandé AVANT la fin de l'éxecution du controller
            // l'event kernel.view est lancé APRES l'éxécution du controller
            // 'kernel.view' => 'onKernelController',
        ];
    }
}
