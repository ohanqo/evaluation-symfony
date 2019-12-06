<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact.index")
     */
    public function index(Request $request, \Swift_Mailer $mailer, ArtworkRepository $artworkRepository)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendConfirmationMail($mailer);

            $this->addFlash('notice', '✅ Un email de confirmation vous a été envoyé!');

            return $this->redirectToRoute('home');
        }

        $artwork = $artworkRepository->findOneBy([]);

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'artwork' => $artwork
        ]);
    }

    private function sendConfirmationMail(\Swift_Mailer $mailer)
    {
        $message = new \Swift_Message();

        $message
            ->setFrom('hello@titosalgado.com')
            ->setContentType('text/html')
            ->setSubject('Message bien reçu')
            ->setBody('Message bien reçu');

        $mailer->send($message);
    }
}
