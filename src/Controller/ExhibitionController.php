<?php

namespace App\Controller;

use App\Repository\ExhibitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exhibitions")
 */
class ExhibitionController extends AbstractController
{
    /**
     * @Route("/", name="exhibition.index")
     */
    public function index(ExhibitionRepository $exhibitionRepository)
    {
        $exhibitions = $exhibitionRepository->findAll();
        $upcomingExhibitions = [];

        foreach ($exhibitions as $exhibition) {
            if ($exhibition->isUpcomingExhibition()) {
                array_push($upcomingExhibitions, $exhibition);
            }
        }

        return $this->render('exhibition/index.html.twig', [
            "exhibitions" => $upcomingExhibitions
        ]);
    }
}
