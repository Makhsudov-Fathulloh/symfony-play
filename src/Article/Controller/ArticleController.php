<?php

namespace App\Article\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles')]
    public function index(): Response
    {
        return $this->render('articles/index.html.twig');
    }
}
