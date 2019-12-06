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
     * @Route("/", name="artwork")
     */
    public function index(ArtworkRepository $artworkRepository, CategoryRepository $categoryRepository)
    {
        $artworks = [];
        $categories = $categoryRepository->findAll();

        foreach ($categories as $category) {
            $artworks[$category->getName()] = $artworkRepository->findBy(['category' => $category]);
        }

        return $this->render('artworks/index.html.twig', [
            'artworksByCategory' => $artworks
        ]);
    }
}
