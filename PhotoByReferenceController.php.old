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

class PhotoByReferenceController extends AbstractController
{
    private PhotoRepository $photoRepository;
    private EntityManagerInterface $entityManager;

    private const COMMANDE_INTERNE_TYPE = 'commande_interne';
    private const PRESTATION_TYPE = 'prestation';

    public function __construct(PhotoRepository $photoRepository, EntityManagerInterface $entityManger)
    {
        $this->photoRepository = $photoRepository;
        $this->entityManager = $entityManger;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $referencePrestation = $request->query->get('referencePrestation');
        $referenceCommandeInterne = $request->query->get('referenceCommandeInterne');
        
        // Vérifier qu'au moins une référence est fournie
        if (empty($referenceCommandeInterne) && empty($referencePrestation)) {
            return new JsonResponse(['error' => 'No arguments sent'], Response::HTTP_BAD_REQUEST);
        }
        
        $photosArray = [];
        $commande = null;
        
        // Déterminer le type de commande et récupérer la commande correspondante
        if (!empty($referenceCommandeInterne)) {
            $commande = $this->getCommandeByReference($referenceCommandeInterne, self::COMMANDE_INTERNE_TYPE);
        } elseif (!empty($referencePrestation)) {
            $commande = $this->getCommandeByReference($referencePrestation, self::PRESTATION_TYPE);
        }
        
        // Vérifier si la commande est trouvée
        if (!$commande) {
            return new JsonResponse(['error' => 'No reference provided or invalid reference.'], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer les photos associées à la commande
        foreach ($commande->getPhotos() as $photo) {
            $photosArray[] = [
                'id' => $photo->getId(),
                'filePath' => $photo->getFilePath(),
                // Ajouter d'autres champs si nécessaire
            ];
        }

        // Vérifier si des photos ont été trouvées
        if (empty($photosArray)) {
            return new JsonResponse(['error' => 'No photos found for this reference.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($photosArray, Response::HTTP_OK);
    }

    private function getCommandeByReference(string $reference, string $type): ?Commande
    {
        $criteria = $type === self::COMMANDE_INTERNE_TYPE
            ? ['referenceCommandeInterne' => $reference]
            : ['referencePrestation' => $reference];

        return $this->entityManager->getRepository(Commande::class)->findOneBy($criteria);
    }
}
