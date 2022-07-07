<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $manager): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $manager->getRepository(Post::class)->findAll()
        ]);
    }

    #[Route('/post/{id}', name:"single_post", methods:["GET"], requirements:["id" => "\d+"])]
    public function single(int $id, ManagerRegistry $manager) :Response
    {
        $post = $manager->getRepository(Post::class)->find($id);
        return $this->render('post/single.html.twig', [
            'post' => $post
        ]);
    }

    #[Route("/post/add", name:"add_post", methods:["GET", "POST"])]
    public function add(Request $request, ManagerRegistry $manager) :Response
    {
        // On créé un nouvel article
        $post = new Post;
        // On génère le formulaire
        $form = $this->createFormBuilder($post)
                ->add('title', TextType::class, [
                    'label' => "Titre de l'article"
                ])
                ->add('description', TextareaType::class, [
                    'label' => "Contenu"
                ])
                ->add('submit', SubmitType::class, [
                    'label' => "Envoyer"
                ])
                ->getForm();
        
                // On associe les informations contenues dans la Request au formulaire
        $form->handleRequest($request);
        // On vérifie que le formulaire a été soumis et que les données reçues correspondent à ce qui est attendu
        if ($form->isSubmitted() && $form->isValid()) {
            // On attribue la valeur du moment présent à createdAt
            $post->setCreatedAt(new \DateTime());
            // On enregistre l'article en BDD
            $manager->getRepository(Post::class)->add($post, true);
            // On redirige l'utilisateur vers la page single pour voir les informations de l'article créé
            return $this->redirectToRoute('single_post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/add.html.twig', [
            'formPost' => $form
        ]);
    }

    #[Route('/post/{id}/update', name:'update_post', methods:["GET", "POST"], requirements:['id'=>"\d+"])]
    public function update(Post $post, Request $request, ManagerRegistry $manager) :Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Post::class)->add($post, true);
            return $this->redirectToRoute('single_post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/{id}/delete', name:"delete_post", methods:["GET"], requirements:['id' => "\d+"])]
    public function delete(Post $post, ManagerRegistry $manager) :Response
    {
        $manager->getRepository(Post::class)->remove($post, true);
        return $this->redirectToRoute('app_post');
    }
}
