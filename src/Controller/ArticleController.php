<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Images;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
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
      *@Route("/article/{id}", name="detail_article")
      */
     public function article(Article $article)
     {
          return $this->render('articles/detail.html.twig', [
               "article" => $article,
          ]);
     }
}
