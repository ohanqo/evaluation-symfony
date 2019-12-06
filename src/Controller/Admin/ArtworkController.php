<?php

namespace App\Controller\Admin;

use App\Entity\Artwork;
use App\Form\ArtworkType;
use App\Repository\ArtworkRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/artworks")
 */
class ArtworkController extends AbstractController
{
    /**
     * @Route("/", name="admin.artwork.index")
     */
    public function index(ArtworkRepository $artworkRepository)
    {
        $artworks = $artworkRepository->findAll();

        return $this->render('admin/artwork/index.html.twig', [
            'artworks' => $artworks
        ]);
    }

    /**
     * @Route("/create", name="admin.artwork.create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, FileService $fileService)
    {
        $model = new Artwork();
        $form = $this->createForm(ArtworkType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $picture = $form['picture']->getData();

            if ($picture instanceof UploadedFile) {
                $fileService->upload($picture, 'img/artworks');
                $entity->setPicture($fileService->getFileName());
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('notice', "L'œuvre a été ajouté.");
        }

        return $this->render('admin/artwork/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin.artwork.edit")
     */
    public function update(Request $request, EntityManagerInterface $entityManager, int $id, ArtworkRepository $artworkRepository, FileService $fileService)
    {
        $artwork = $artworkRepository->find($id);

        if (!$artwork) {
            throw $this->createNotFoundException("L'œuvre n'existe pas.");
        }

        $previousImage = $artwork->getPicture();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $picture = $form['picture']->getData();

            if ($picture instanceof UploadedFile) {
                $fileService->upload($picture, 'img/artworks');
                $entity->setPicture($fileService->getFileName());

                if (file_exists("img/artworks/{$previousImage}")) {
                    $fileService->remove('img/artworks', $previousImage);
                }
            } else {
                $entity->setPicture($previousImage);
            }

            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('notice', "L'œuvre a été modifié.");
            return $this->redirectToRoute('admin.artwork.index');
        }

        return $this->render('admin/artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin.artwork.delete")
     */
    public function destroy(int $id, ArtworkRepository $artworkRepository, EntityManagerInterface $entityManager, FileService $fileService)
    {
        $artwork = $artworkRepository->find($id);

        if (!$artwork) {
            throw $this->createNotFoundException("L'œuvre n'existe pas.");
        }
        $entityManager->remove($artwork);
        $entityManager->flush();

        if (file_exists("img/artworks/{$artwork->getPicture()}")) {
            $fileService->remove('img/artworks', $artwork->getPicture());
        }

        $this->addFlash('notice', "L'œuvre a été supprimée.");
        return $this->redirectToRoute('admin.artwork.index');
    }
}
