<?php

namespace App\Article\Controller;

use App\Article\Entity\Article;
use App\Article\Form\ArticleFormType;
use App\Article\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $em;
    private ArticleRepository $articleRepository;

    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/', name: 'articles', methods: ['GET'])]
    public function index(): Response
    {
        $articles = $this->articleRepository->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/create_article', name: 'create_article')]
    public function create(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newArticle = $form->getData();

            $image = $form->get('image')->getData();
            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newArticle->setImage('/uploads/' . $newFileName);
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
    #[Route('/edit/{id}', name: 'edit_article')]
    public function edit($id, Request $request): Response
    {
        $article = $this->articleRepository->find($id);
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        $imagePath = $form->get('image')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($article->getImage() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $article->getImage()
                    )) {
                        $this->getParameter('kernel.project_dir') . $article->getImage();
                    }
                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $article->setImage('/uploads/' . $newFileName);
                    $this->em->flush();

                    return $this->redirectToRoute('articles');
                }
            } else {
                $article->setTitle($form->get('title')->getData());
                $article->setDescription($form->get('description')->getData());
                $article->setText($form->get('text')->getData());

                $this->em->flush();
                return $this->redirectToRoute('articles');
            }
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    // ------------------------------------------------------------------------------------------

    #[Route('/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_article')]
    public function delete($id): Response
    {
        $article = $this->articleRepository->find($id);
        $this->em->remove($article);
        $this->em->flush();

        return $this->redirectToRoute('articles');
    }

    // ------------------------------------------------------------------------------------------
    #[Route('/article{id}', name: 'show_article', methods: ['GET'])]
    public function show($id): Response
    {
        $article = $this->articleRepository->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }
}
