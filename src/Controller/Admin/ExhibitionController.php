<?php

namespace App\Controller\Admin;

use App\Entity\Exhibition;
use App\Form\ExhibitionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/exhibitions")
 */
class ExhibitionController extends AbstractController
{
    /**
     * @Route("/create", name="admin.exhibition.create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $model = new Exhibition();
        $form = $this->createForm(ExhibitionType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash('notice', "L'exposition a été ajoutée.");
        }

        return $this->render('admin/exhibition/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
