<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavigationController extends AbstractController
{
    public function nav($app, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('inc/nav.html.twig', [
            'categories' => $categories,
            'route_name' => $app->get('_route')
        ]);
    }

    public function footer(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('inc/footer.html.twig', [
            'categories' => $categories,
        ]);
    }
}