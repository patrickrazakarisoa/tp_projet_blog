<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Article;
use App\Entity\Comments;
use App\Form\ArticleType;
use App\Form\CommentsType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
      * @Route("/details/article/{id}", name="detail_article")
      */
    public function details($id, ArticleRepository $articleRepository, Request $request, CommentsRepository $commentsRepository)
    {
        $article = $articleRepository->findOneBy(['id' => $id]);
        $comments = $commentsRepository->findAll();

        // Partie commentaires
        // On crée le commentaire "vierge"
        $comment = new Comments;

        // On génère le formulaire
        $form = $this->createForm(CommentsType::class, $comment);

        $form->handleRequest($request);

        // Traitement du formulaire
        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setArticle($article);

            // On récupère le contenu du champ parentid
            $parentid = $form->get("parentid")->getData();

            // On va chercher le commentaire correspondant
            $em = $this->getDoctrine()->getManager();

            if($parentid != null){
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }

            // On définit le parent
            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('detail_article', ['id' => $article->getId()]);
        }

        return $this->render('articles/detail.html.twig', [
            'article' => $article, 
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }
}
