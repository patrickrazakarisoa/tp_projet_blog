<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Images;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
      *@Route("/{id}", name="detail_article")
      */
     public function article($id, Request $request)
     {
          $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

          $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
          $new_commentaire = new Commentaire;
          $form = $this->createForm(CommentaireType::class, $new_commentaire);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               // setDate(new \DateTime()) permet de génerer une date automatique lors de la validation
               $new_commentaire->setDate(new \DateTime());
               $new_commentaire->setArticle($article);
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($new_commentaire);
               $entityManager->flush();

               // return $this->redirectToRoute('detail_article');
          }



          return $this->render('articles/detail.html.twig', [
               "id" => $id,
               "article" => $article,
               "commentaires" => $commentaires,
               "form" => $form->createView()

          ]);
     }





     /**
      * @Route("/admin/add-article", name="add-article")
      */
     public function addProduct(Request $request)
     {
          $new_article = new Article;
          $form = $this->createForm(ArticleType::class, $new_article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

               $images = $form->get('images')->getData();

               // On boucle sur les images
               foreach ($images as $image) {
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    $image->move(
                         $this->getParameter('images_directory'),
                         $fichier
                    );

                    // On crée l'image dans la base de données
                    $img = new Images();
                    $img->setName($fichier);
                    $new_article->addImage($img);
               }

               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($new_article);
               $entityManager->flush();

               return $this->redirectToRoute('affichage-article');
          }

          // $this->addFlash("article_add_success", "Votre article a été ajouté avec succès");
          return $this->render('articles/addarticle.html.twig', [
               "form" => $form->createView()
          ]);
     }

     /**
      * @Route("/admin/{id}", name="edit-article")
      */
     public function editArticle($id, Request $request)
     {
          $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
          $form = $this->createForm(ArticleType::class, $article);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {


               $images = $form->get('images')->getData();

               // On boucle sur les images
               foreach ($images as $image) {
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    $image->move(
                         $this->getParameter('images_directory'),
                         $fichier
                    );

                    // On crée l'image dans la base de données
                    $img = new Images();
                    $img->setName($fichier);
                    $article->addImage($img);
               }

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
      * @Route("/admin/delete-article/{id}", name="delete-article")
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

     /**
      * @Route("/supprime/image/{id}", name="annonces_delete_image", methods={"DELETE"})
      */
     public function deleteImage(Images $image, Request $request)
     {
          $data = json_decode($request->getContent(), true);

          // On vérifie si le token est valide
          if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
               // On récupère le nom de l'image
               $nom = $image->getName();
               // On supprime le fichier
               unlink($this->getParameter('images_directory') . '/' . $nom);

               // On supprime l'entrée de la base
               $em = $this->getDoctrine()->getManager();
               $em->remove($image);
               $em->flush();

               // On répond en json
               return new JsonResponse(['success' => 1]);
          } else {
               return new JsonResponse(['error' => 'Token Invalide'], 400);
          }
     }
}
