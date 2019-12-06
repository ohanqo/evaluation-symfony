<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category.index")
     */
    public function index()
    {
        return $this->redirectToRoute('artwork.index');
    }

    /**
     * @Route("/{id}", name="category.show")
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie n'existe pas.");
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
