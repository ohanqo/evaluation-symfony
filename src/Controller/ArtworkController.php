<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artworks")
 */
class ArtworkController extends AbstractController
{
    /**
     * @Route("/", name="artwork.index")
     */
    public function index(ArtworkRepository $artworkRepository, CategoryRepository $categoryRepository)
    {
        $artworks = [];
        $categories = $categoryRepository->findAll();

        foreach ($categories as $category) {
            $artworks[$category->getName()] = $artworkRepository->findBy(['category' => $category]);
        }

        return $this->render('artwork/index.html.twig', [
            'artworksByCategory' => $artworks
        ]);
    }

    /**
     * @Route("/{id}", name="artwork.show")
     */
    public function show(int $id, ArtworkRepository $artworkRepository)
    {
        $artwork = $artworkRepository->find($id);

        if (!$artwork) {
            throw $this->createNotFoundException("L'œuvre n'existe pas.");
        }

        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork
        ]);
    }
}
