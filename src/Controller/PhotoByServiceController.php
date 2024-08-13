<?php

// src/Controller/PhotoByReferenceController.php
namespace App\Controller;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotoByServiceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $serviceReference = $request->query->get('referencePrestation');

        if (empty($serviceReference)) {
            return new JsonResponse(['error' => 'No serviceReference provided.'], Response::HTTP_BAD_REQUEST);
        }

        $photosArray = [];
        $order = $this->entityManager->getRepository(Command::class)
            ->findOneBy(['serviceReference' => $serviceReference]);

        if (!$order) {
            return new JsonResponse(['error' => 'Invalid serviceReference.'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($order->getPhotos() as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
                'createdAt' => $photo->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $photo->getUpdatedAt()->format('Y-m-d H:i:s')
            ];
        }

        if (empty($photosArray)) {
            return new JsonResponse(['error' => 'No photos found for this reference.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($photosArray, Response::HTTP_OK);
    }
}
