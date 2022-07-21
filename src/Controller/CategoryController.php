<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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

    // L'attribut methods indique les méthodes autorisées pour accéder à cette page: GET, POST, PUT, PATCH, DELETE
    // Les requirements donnent des indications sur les paramètres attendus dans l'url. Ici l'id ne doit comporter que des chiffres
    #[Route('/category/{id}', name:'single_category', methods:["GET"], requirements:['id'=> '\d+'])]
    /**
     * Depuis Symfony 6, on utilise le ManagerRegistry à la place de l'EntityManagerInterface pour se connecter au Repository
     *
     * @param integer $id
     * @param ManagerRegistry $manager
     * @return Response
     */
    public function single(int $id, ManagerRegistry $manager): Response
    {
        // La méthode find du repository est une méthode permettant de retourner un élément d'une table en fonction de son id.
        $category = $manager->getRepository(Category::class)->find($id);
        return $this->render('category/single.html.twig', [
            'category' => $category
        ]);
    }

    #[Route("/category/add", name:"add_category", methods:["GET", "POST"])]
    public function add (Request $request, ManagerRegistry $manager) :Response
    {
        // On créé un objet catégorie vide à passer au formulaire pour le remplir
        $category = new Category;

        // CreateFormBuilder permet de créer un formulaire
        $form = $this->createFormBuilder($category)
                // Pour chaque champ de l'objet, on va ajouter un input
                // Cette input contient le nom du champ de la table et le type d'input
                // Ici on créé un input de type text pour récupérer le name de la catégorie
                ->add('name', TextType::class, [
                    'label' => "Nom de la catégorie"
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Enregistrer'
                ])
                // Get form permet de stocker le foormulaire préconçu dans la variable $form
                ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            try {
                $manager->getRepository(Category::class)->add($category, true);
                
                // Message flash qui s'affichera sur la page de redirection pour confirmer l'opération
                $this->addFlash('success', "La catégorie ".$category->getName()." a bien été ajoutée");
            } catch (\Throwable $th) {
                // Message flash qui s'affichera sur la page de redirection pour indiquer une erreur lors de l'enregistrement
                $this->addFlash('danger', "La catégorie ".$category->getName()." n'a pas pu être enregsitrée");
            }

            return $this->redirectToRoute('app_category');
        }
        
        // Lorsque l'on doit afficher un formulaire sur une page, on doit utiliser la méthode renderForm
        // et non render pour générer le template.
        return $this->renderForm('category/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/category/{id}/update", name:"update_category", methods:["GET", "POST"], requirements:['id' => '\d+'])]
    public function update(Category $category, Request $request, ManagerRegistry $manager) :Response
    {
        // Avec la méthode createForm, on charge le formulaire qui a été sur une class dédiée (CategoryType)
        $form = $this->createForm(CategoryType::class, $category);
        // On associe la request on formulaire
        $form->handleRequest($request);
        // On s'assure que le formulaire a bien été soumis et que les données reçues sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // On utilise la méthode add qui si elle détecte un id dans l'objet va exécuter une requête update
            $manager->getRepository(Category::class)->add($category, true);
            return $this->redirectToRoute('single_category', ['id' => $category->getId()]);
        }

        return $this->renderForm('category/update.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route("/categorier/{id}/delete", name:'delete_category', methods:["GET"], requirements:['id'=> '\d+'])]
    public function delete(Category $category, ManagerRegistry $manager) :Response
    {
        // Grâce au manager, on va charger le repository et la méthode remove.
        // Cette méthode remove va mettre en queue la suppression de la catégorie
        $manager->getRepository(Category::class)->remove($category, true);

        $this->addFlash("warning", "La catégorie '".$category->getName(). "' a bien été supprimée.");
        // On redirige l'utilisateur vers la page affichant toutes les catégories
        return $this->redirectToRoute('app_category');
    }
}
