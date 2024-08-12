<?php

// src/Controller/PhotoByReferenceController.php
namespace App\Controller;

use App\Entity\Commande;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotoByPrestationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $referencePrestation = $request->query->get('referencePrestation');

        if (empty($referencePrestation)) {
            return new JsonResponse(['error' => 'No referencePrestation provided'], Response::HTTP_BAD_REQUEST);
        }

        $photosArray = [];
        $commande = $this->entityManager->getRepository(Commande::class)
            ->findOneBy(['referencePrestation' => $referencePrestation]);

        if (!$commande) {
            return new JsonResponse(['error' => 'Invalid referencePrestation.'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($commande->getPhotos() as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
            ];
        }

        if (empty($photosArray)) {
            return new JsonResponse(['error' => 'No photos found for this reference.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($photosArray, Response::HTTP_OK);
    }
}
