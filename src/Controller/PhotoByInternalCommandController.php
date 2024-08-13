<?php

// src/Controller/PhotoByReferenceController.php
namespace App\Controller;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoByInternalCommandController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $internalCommandReference = $request->query->get('referenceCommandeInterne');

        if (empty($internalCommandReference)) {
            return new JsonResponse(['error' => 'No internalCommandReference provided'], Response::HTTP_BAD_REQUEST);
        }

        $photosArray = [];
        $order = $this->entityManager->getRepository(Command::class)
            ->findOneBy(['internalCommandReference' => $internalCommandReference]);

        if (!$order) {
            return new JsonResponse(['error' => 'Invalid internalCommandReference.'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($order->getPhotos() as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
                'createdAt' => $photo->getCreatedAt(),
                'updatedAt' => $photo->getUpdatedAt()
            ];
        }

        if (empty($photosArray)) {
            return new JsonResponse(['error' => 'No photos found for this reference.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($photosArray, Response::HTTP_OK);
    }

}
