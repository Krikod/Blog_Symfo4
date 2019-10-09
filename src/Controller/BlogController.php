<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
//        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
//            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', array(
            'title' => 'Accueil'
        ));
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     *
     */
    public function show(Article $article)
    {
//        Injection de dépendances !!!
//        $repo = $this->getDoctrine()->getRepository(Article::class);
//        On s'en passe grâce à l'injection de $repo.
//        $article = $repo->find($id); // On s'en passe grâce au Param Converter
//        et injection Article $article et on supprime arg $id

        return $this->render('blog/show.html.twig', array(
            'article' => $article
        ));
    }
}
