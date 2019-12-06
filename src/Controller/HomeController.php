<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArtworkRepository $artworkRepository)
    {
        $artworks = $artworkRepository->findBy([], null, 4);

        return $this->render('home/index.html.twig', [
            'artworks' => $artworks,
        ]);
    }

    /**
     * @Route("/legal", name="home.legal")
     */
    public function legal()
    {
        return $this->render('home/legal.html.twig');
    }
}
