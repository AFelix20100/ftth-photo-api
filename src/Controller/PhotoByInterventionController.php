<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PhotoByInterventionController extends AbstractController
{
    private InterventionRepository $interventionRepository;

    public function __construct(InterventionRepository $interventionRepository)
    {
        $this->interventionRepository = $interventionRepository;
    }

    public function __invoke($numeroIntervention): JsonResponse
    {
        $intervention = $this->interventionRepository->findOneBy(['numeroIntervention' => $numeroIntervention]);
        if (!$intervention) {
            return new JsonResponse(['error' => 'Intervention not found.'], Response::HTTP_NOT_FOUND);
        }

        $photos = $intervention->getPhotos();

        if ($photos->isEmpty()) {
            return new JsonResponse(['error' => 'No photos found for this intervention.'], Response::HTTP_NOT_FOUND);
        }

        // Convert photos to an array of arrays to be returned as JSON
        $photosArray = [];
        foreach ($photos as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
                // Add other fields as needed
            ];
        }

        return new JsonResponse($photosArray, Response::HTTP_OK);
    }
}
