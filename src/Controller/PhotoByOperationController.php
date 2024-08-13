<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoByOperationController extends AbstractController
{
    private OperationRepository $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $operationReference = $request->query->get('referenceIntervention');

        $operation = $this->operationRepository->findOneBy(['operationReference' => $operationReference]);

        if (!$operation) {
            return new JsonResponse(['error' => 'Operation not found.'], Response::HTTP_NOT_FOUND);
        }
        foreach ($operation->getPhotos() as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getImageName(),
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