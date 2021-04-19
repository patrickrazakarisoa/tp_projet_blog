<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{
     /**
      * @Route("/", name="affichage-article")
      */
     public function index()
     {
          $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
          return $this->render('articles/accueil.html.twig', [
               'articles' => $articles
          ]);
     }

     /**
      *@Route("/article/{id}", name="detail_article")
      */
     public function article($id)
     {
          $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
          return $this->render('articles/detail.html.twig', [
               "id" => $id,
               "article" => $article
          ]);
     }




     /**
      * @Route("/add-article", name="add-article")
      */
     public function addProduct(Request $request)
     {
          $new_article = new Article;
          $form = $this->createForm(ArticleType::class, $new_article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($new_article);
               $entityManager->flush();

               return $this->redirectToRoute('add-article');
          }

          // $this->addFlash("article_add_success", "Votre article a été ajouté avec succès");
          return $this->render('articles/addarticle.html.twig', [
               "form" => $form->createView()
          ]);
     }

     /**
      * @Route("/edit-article/{id}", name="edit-article")
      */

     public function editArticle($id, Request $request)
     {
          $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
          $form = $this->createForm(ArticleType::class, $article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->flush();

               return $this->redirectToRoute('affichage-article');
          }

          // $this->addFlash("article_edit_success", "Votre produit a été modifier avec succès");
          return $this->render('articles/addarticle.html.twig', [
               "id" => $id,
               "article" => $article,
               "form" => $form->createView()
          ]);
     }

     /**
      * @Route("/delete-article/{id}", name="delete-article")
      */

     public function deleteArticle($id)
     {
          $entityManager = $this->getDoctrine()->getManager();
          $articles = $entityManager->getRepository(Article::class)->find($id);

          $entityManager->remove($articles);
          $entityManager->flush();

          $this->addFlash("article_delete_success", "Votre article a été supprimer avec succès");
          return $this->redirectToRoute('affichage-article');
     }
}
