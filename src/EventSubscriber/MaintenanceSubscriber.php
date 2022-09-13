<?php

namespace App\EventSubscriber;

use Twig\Environment;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;
/**
* Constructor
*/
public function __construct( Environment $twig)
{
    $this->twig = $twig;

}


    public function onKernelResponse(ResponseEvent $event): void
    {
        

    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
