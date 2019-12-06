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
    private $fileService;
    private $artworkRepository;
    private $entityManager;
    private $pictureFolderPath = 'img/artworks';

    public function __construct(
        FileService $fileService,
        ArtworkRepository $artworkRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->fileService = $fileService;
        $this->artworkRepository = $artworkRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="admin.artwork.index")
     */
    public function index()
    {
        $artworks = $this->artworkRepository->findAll();

        return $this->render('admin/artwork/index.html.twig', [
            'artworks' => $artworks
        ]);
    }

    /**
     * @Route("/create", name="admin.artwork.create")
     */
    public function create(Request $request)
    {
        $model = new Artwork();
        $form = $this->createForm(ArtworkType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $picture = $form['picture']->getData();

            if ($picture instanceof UploadedFile) {
                $this->fileService->upload($picture, $this->pictureFolderPath);
                $entity->setPicture($this->fileService->getFileName());
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->addFlash('notice', "L'œuvre a été ajouté.");
        }

        return $this->render('admin/artwork/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin.artwork.edit")
     */
    public function update(Request $request, int $id)
    {
        $artwork = $this->artworkRepository->find($id);

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
                $this->fileService->upload($picture, $this->pictureFolderPath);
                $entity->setPicture($this->fileService->getFileName());

                $this->fileService->remove($this->pictureFolderPath, $previousImage);
            } else {
                $entity->setPicture($previousImage);
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

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
    public function destroy(int $id)
    {
        $artwork = $this->artworkRepository->find($id);

        if (!$artwork) {
            throw $this->createNotFoundException("L'œuvre n'existe pas.");
        }

        $this->entityManager->remove($artwork);
        $this->entityManager->flush();

        $this->fileService->remove($this->pictureFolderPath, $artwork->getPicture());

        $this->addFlash('notice', "L'œuvre a été supprimée.");
        return $this->redirectToRoute('admin.artwork.index');
    }
}
