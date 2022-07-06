<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercice', name: 'app_exercice')]
    public function index(): Response
    {
        $langages = [
            [
                "name" => "HTML",
                "description" => "Langage d'intégration représentant le squelette d'une page"
            ],
            [
                "name" => "CSS",
                "description" => "Langage d'intégration représentant le design d'une page"
            ],
            [
                "name" => "Javascript",
                "description" => "Langage de développement apportant de l'intéraction à l'utilisateur"
            ],
        ];

        return $this->render('exercice/index.html.twig', [
            'langages' => $langages
        ]);
    }
}
