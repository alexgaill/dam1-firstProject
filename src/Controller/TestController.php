<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController {

    /**
     * Première page affichant le mot "Hello"
     *
     * @return Response
     */
    public function hello (): Response
    {
        return new Response("Hello");
    }

    #[Route(path: "/bye", name:"bye")]
    public function bye (): Response
    {
        return new Response("<h1> Byebye </h1>");
    }
}