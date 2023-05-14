<?php

namespace App\Article\Controller;

use App\Article\Entity\Article;
use App\Article\Form\ArticleFormType;
use App\Article\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $em;
    private $articleRepository;

    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/', methods: ['GET'], name: 'articles')]
    public function index(): Response
    {
        $articles = $this->articleRepository->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/create', name: 'create_article')]
    public function create(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newArticle = $form->getData();

            $imagePath = $form->get('image_path')->getData();
            if ($imagePath){
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                }catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newArticle->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newArticle);
            $this->em->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render('articles/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/article{id}', methods: ['GET'], name: 'show')]
    public function show($id): Response
    {
        $article = $this->articleRepository->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }
}
