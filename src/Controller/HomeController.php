<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{


     // /**
     //  * @route("/connexion")
     //  */
     // public function connexion()
     // {
     //      return new Response("<h1> Bonjour ! </h1>");
     // }

     // /**
     //  * @route("/connexion/{numero}", requirements={"numero"="\d+"})
     //  */
     // public function connexion_number($numero)
     // {
     //      return new Response("<h1> Vous êtes l'utilisation numéro $numero </h1>");
     // }

     // /**
     //  * @route("/connexion/{name?Patrick}")
     //  */
     // public function connexion_user($name)
     // {
     //      return new Response("<h1> Bonjour $name </h1>");
     // }

     /**
      * @route("/moi", name="page_acceuil")
      */
     public function home()
     {

          $posts = [
               [
                    'title' => 'Article 1',
                    'description' => 'Lorem ipsum',
                    'category' => 'animaux'
               ],
               [
                    'title' => 'Article 2',
                    'description' => 'Lorem ipsum',
                    'category' => 'enfant'
               ],
               [
                    'title' => 'Article 3',
                    'description' => 'Lorem ipsum',
                    'category' => 'deco'
               ],
          ];

          return $this->render('articles/accueil.html.twig', [
               'posts' => $posts,
          ]);
     }

     /**
      * @route("/moi/{id}", name="detail_article_lol")
      */
     public function detail_article($id)
     {

          $posts = [
               [
                    'title' => 'Article 1',
                    'description' => 'Lorem ipsum',
                    'category' => 'animaux'
               ],
               [
                    'title' => 'Article 2',
                    'description' => 'Lorem ipsum',
                    'category' => 'enfant'
               ],
               [
                    'title' => 'Article 3',
                    'description' => 'Lorem ipsum',
                    'category' => 'deco'
               ],
          ];


          return $this->render('articles/detail.html.twig', [
               "id" => $id,
               "posts" => $posts,
          ]);
     }
}
