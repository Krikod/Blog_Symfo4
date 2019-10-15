<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
//    Méthode plus "create" mais "form", peut être appelée par 2 routes différentes !
// L'id est dans $article grâce au ParamConverter, et parfois peut être nul.
    public function form(Article $article = null, Request $request, ObjectManager $manager)
        // Dep.Injection pour analyser la requête GET ou POST
//        connaître les données qui ont été passées
    {
        if (!$article) {
            $article = new Article();
        }

        //        Simplifier le form et utiliser form_row dans create !!
//        $form = $this->createFormBuilder($article)
//            ->add('title')
//            ->add('content')
//            ->add('image')
//            ->getForm();
//        Les infos passées sont dans la Request, donc on demande de l'analyser
//        Et l'article vide $article va être lié/bindé aux données/champs du form !

//        Avec ArticleType, remplace createFormBuilder/->add:
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new  \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', array(
                'id' => $article->getId()
            ));
        }
        return $this->render('blog/create.html.twig', array(
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null // null, donc editMode
        ));





//        $form = $this->createFormBuilder($article)
//            ->add('title', TextType::class, array(
////                Attr si option Attribut d'HTML
//            'attr' => array(// classe css, identifiant, .
//                'placeholder' => "Titre de l'article",
////                'class' => 'form-control'
//            )
//            ))
//            ->add('content', TextareaType::class, array(
//                'attr' => array(
//                    'placeholder' => "Contenu de l'article",
////                    'class' => 'form-control'
//                )
//            ))
//            // add type que si différent de l'entité !!!
//                // sinon le form le prend de l'Entity !
//            ->add('image', TextType::class, array(
//                'attr' => array(
//                    'placeholder' => "Image de l'article",
////                    'class' => 'form-control' // pour form bootstrap
//                )
//            ))
////            Pas ici, pour ne pas bloquer le bouton sur Enregistrer
////            ->add('save', SubmitType::class, array(
////                'label' => 'Enregistrer'
////            ))
//            ->getForm();

//        return $this->render('blog/create.html.twig', array(
//            'formArticle' => $form->createView()
//        ));
    }
//    /blog/{id} après /blog/new sinon "new" sera pris pour
// id... Ou requirements

    /**
     * @Route("/blog/{id}", name="blog_show")
     *
     */
    public function show(Article $article, Request $request, ObjectManager $manager)
    {
//        Injection de dépendances !!!
//        $repo = $this->getDoctrine()->getRepository(Article::class);
//        On s'en passe grâce à l'injection de $repo.
//        $article = $repo->find($id); // On s'en passe grâce au Param Converter
//        et injection Article $article et on supprime arg $id

        $comment = new Comment();
//      todo commentaire ne s'affiche qu'au 2eme envoi

        // Form pour commentaires
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new  \DateTime());
            $comment->setArticle($article);

            $manager->persist($comment);
        }

        $nbComments = count($article->getComments());
//        dump($nbComments);
        $manager->flush();

        return $this->render('blog/show.html.twig', array(
            'article' => $article,
            'comment' => $comment,
            'nbComments' => $nbComments,
            'formComment' => $commentForm->createView()
        ));
    }
}
