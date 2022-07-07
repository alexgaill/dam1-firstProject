<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $manager): Response
    {
        /**
         * Pour récupérer les données d'une table on doit se connecter à doctrine avec un manager.
         * Ici on utilise l'EntityManager qui permet de faire le lien entre une entité et une table.
         * Les méthodes permettant de récupérer les données sont dans une class appelée un Repository.
         * 
         * On doit donc demander à notre manager de récupérer le Repository associé à l'entité qu'on appelle.
         * Il charge le bon repository car dans l'entité on a passé en attribut l'information du repository associé.
         */
        $categories = $manager->getRepository(Category::class)->findAll();

        // dump() permet de débugguer et d'afficher des informations dans la barre de debug (profiler)
        // dump($categories);

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
