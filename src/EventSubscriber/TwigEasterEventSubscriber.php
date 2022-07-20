<?php

namespace App\EventSubscriber;

use Twig\Environment;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigEasterEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private Environment $twig) {}

    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('EasterEgg', "There is no EasterEgg");
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
