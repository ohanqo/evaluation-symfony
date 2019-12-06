<?php

namespace App\Controller;

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
    public function index()
    {
        return $this->render('artworks/index.html.twig');
    }
}
