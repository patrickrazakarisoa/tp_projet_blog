<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/user/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $contact = $form->getData();

            // dd($contact);

            // ici nous enverros le mail
            $message = (new \Swift_Message('Nouveau Contact'))
                // On attribue l'expéditeur
                ->setFrom($contact['email'])
                // On attribue le destinataire
                ->setTo('switchkun2902@gmail.com')

                // On crée le maessage avec la vue Twig
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        compact('contact')
                    ),
                    'text/html'
                );

            // On envoye le message
            $mailer->send($message);

            $this->addFlash('message', 'Le message a bien été envoyé.');
            return $this->redirectToRoute('affichage-article');
        }

        return $this->render('contact/index.html.twig', [
            'ContactForm' => $form->createView(),
        ]);
    }
}
