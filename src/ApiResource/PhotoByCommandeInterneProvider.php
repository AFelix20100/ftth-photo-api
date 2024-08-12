<?php

namespace App\ApiResource;

use App\Repository\CommandeRepository;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PhotoByCommandeInterneProvider
{
    private $commandeRepository;
    private $photoRepository;

    public function __construct(CommandeRepository $commandeRepository, PhotoRepository $photoRepository)
    {
        $this->commandeRepository = $commandeRepository;
        $this->photoRepository = $photoRepository;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $referenceCommandeInterne = $request->query->get('referenceCommandeInterne');

        if (empty($referenceCommandeInterne)) {
            return new JsonResponse(['error' => 'No referenceCommandeInterne provided.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $commande = $this->commandeRepository->findOneBy(['referenceCommandeInterne' => $referenceCommandeInterne]);

        if (!$commande) {
            return new JsonResponse(['error' => 'No command found for this reference.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $photos = $this->photoRepository->findBy(['commande' => $commande]);

        $photosArray = [];
        foreach ($photos as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
                // Add other fields as needed
            ];
        }

        return new JsonResponse(['photos' => $photosArray], JsonResponse::HTTP_OK);
    }
}
