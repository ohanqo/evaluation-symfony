<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ContactController extends AbstractController
{
    private $artworkRepository;
    private $mailer;
    private $twig;

    public function __construct(
        ArtworkRepository $artworkRepository,
        \Swift_Mailer $mailer,
        Environment $twig
    )
    {
        $this->artworkRepository = $artworkRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @Route("/contact", name="contact.index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendConfirmationMail($form->getData());

            $this->addFlash('notice', '✅ Un email de confirmation vous a été envoyé!');

            return $this->redirectToRoute('home');
        }

        $artwork = $this->artworkRepository->findOneBy([]);

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'artwork' => $artwork
        ]);
    }

    private function sendConfirmationMail($data)
    {
        $message = new \Swift_Message();

        $message
            ->setFrom('hello@titosalgado.com')
            ->setContentType('text/html')
            ->setSubject('Message bien reçu')
            ->setBody($this->twig->render('emailing/contact.html.twig', [
                'data' => $data
            ]));

        $this->mailer->send($message);
    }
}
