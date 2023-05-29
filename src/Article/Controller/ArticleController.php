<?php

namespace App\Article\Controller;

use App\Article\Entity\Article;
use App\Article\Form\ArticleFormType;
use App\Article\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function index(ArticleRepository $articleRepository,
                          Request $request,
    ): Response {
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

            $user = $this->getUser();
            $article->setUser($user);

            $image = $form->get('image')->getData();
            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/images',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newArticle->setImage('/public/images' . $newFileName);
            }

            $this->em->persist($newArticle);
            $this->em->flush();

            return $this->redirectToRoute('/');
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
                return $this->redirectToRoute('/');
            }
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    // ------------------------------------------------------------------------------------------

    #[Route('/delete/{id}', name: 'delete_article', methods: ['GET', 'DELETE'])]
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
