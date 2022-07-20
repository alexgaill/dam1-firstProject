<?php
namespace App\EventSubscriber;

use App\Entity\Category;
use Twig\Environment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Evenement rattaché aux controller et à twig pour charger sur chaque page les catégories 
 * et les utiliser dans la navbar
 * 
 * Un fichier d'event implement toujours EventSubscriberInterface afin d'implémenter
 * la méthode static getSubscribedEvents
 * 
 * LA méthode onControllerEvent sera chargée à chaque fois qu'un controller est appelé.
 */ 
class TwigEventSubscriber implements EventSubscriberInterface {

    // private $twig;

    /**
     * On charge les dépendances de twig et le managerRegistry dont on va avoir besoin dans la méthode onControllerEvent
     *
     * @param Environment $twig
     * @param ManagerRegistry $manager
     */
    public function __construct(private Environment $twig, private ManagerRegistry $manager)
    {
        // $this->twig = $twig;
    }

    /**
     * Créé une variable qui sera ajoutée aux variables globales de twig et qui contient toutes les catégories
     *
     * @param ControllerEvent $event
     * @return void
     */
    public function onControllerEvent (ControllerEvent $event)
    {
        $this->twig->addGlobal('categoryMenu', $this->manager->getRepository(Category::class)->findAll());
    }

    /**
     * Active la souscription à l'évènement ControllerEvent
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent'
        ];
    }
}