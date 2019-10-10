<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $request, ObjectManager $manager)
        // Dep.Injection pour analyser la requête GET ou POST
//        connaître les données qui ont été passées
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array(
//                Attr si option Attribut d'HTML
            'attr' => array(// classe css, identifiant, .
                'placeholder' => "Titre de l'article",
//                'class' => 'form-control'
            )
            ))
            ->add('content', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => "Contenu de l'article",
//                    'class' => 'form-control'
                )
            ))
            // add type que si différent de l'entité !!!
                // sinon le form le prend de l'Entity !
            ->add('image', TextType::class, array(
                'attr' => array(
                    'placeholder' => "Image de l'article",
//                    'class' => 'form-control' // pour form bootstrap
                )
            ))
//            Pas ici, pour ne pas bloquer le bouton sur Enregistrer
//            ->add('save', SubmitType::class, array(
//                'label' => 'Enregistrer'
//            ))
            ->getForm();

        return $this->render('blog/create.html.twig', array(
            'formArticle' => $form->createView()
        ));
    }
//    /blog/{id} après /blog/new sinon "new" sera pris pour
// id... Ou requirements
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
