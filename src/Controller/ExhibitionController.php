<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExhibitionController extends AbstractController
{
    /**
     * @Route("/exhibition", name="exhibition")
     */
    public function index()
    {
        return $this->render('exhibition/index.html.twig', [
            'controller_name' => 'ExhibitionController',
        ]);
    }
}
