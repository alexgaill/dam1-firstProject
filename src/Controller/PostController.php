<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
