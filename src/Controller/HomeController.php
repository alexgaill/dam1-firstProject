<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route(path:"/", name:"home")]
    public function home (): Response
    {
        $categories = [
            [
                "name" => "PHP",
                "tag" => "Code"
            ],
            [
                "name" => "Javascript",
                "tag" => "Code"
            ],
            [
                "name" => "Restaurants",
                "tag" => "Voyage"
            ],
            [
                "name" => "Logements",
                "tag" => "Voyage"
            ]
            ];
        // $this->render est une méthode héritée de AbstractController
        // Elle permet de charger un template généré avec twig
        // En deuxième paramètre, on peut passer dans un tableau toutes les informations 
        // que l'on souhaite afficher sur la page
        return $this->render("home/index.html.twig", [
            "categoriesList" => $categories
        ]);
    }
}