<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use App\Entity\Images;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{
     // ---------------------------  PRODUCT  -----------------------------------------

     /**
      * @Route("/", name="affichage-article")
      */
     public function affichage()
     {
          $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
          return $this->render('article/acceuil.html.twig', [
               'articles' => $articles
          ]);
     }

     /**
      *@Route("/product/{id}", name="product")
      */
     public function article($id)
     {
          $article = $this->getDoctrine()->getRepository(Product::class)->find($id);
          return $this->render('article/detail.html.twig', [
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
          $form = $this->createForm(ProductType::class, $new_article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($new_article);
               $entityManager->flush();

               return $this->redirectToRoute('affichage-product');
          }

          $this->addFlash("product_add_success", "Votre article a été ajouté avec succès");
          return $this->render('gestion/addarticle.html.twig', [
               "form" => $form->createView()
          ]);
     }

     /**
      * @Route("/admin/edit-article/{id}", name="edit-article")
      */

     public function editProduct($id, Request $request)
     {
          $article = $this->getDoctrine()->getRepository(Product::class)->find($id);
          $form = $this->createForm(ProductType::class, $article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->flush();

               return $this->redirectToRoute('affichage-article');
          }

          $this->addFlash("product_edit_success", "Votre produit a été modifier avec succès");
          return $this->render('gestion/addproduct.html.twig', [
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
          $products = $entityManager->getRepository(Product::class)->find($id);

          $entityManager->remove($products);
          $entityManager->flush();

          $this->addFlash("article_delete_success", "Votre article a été supprimer avec succès");
          return $this->redirectToRoute('affichage-article');
     }
}
