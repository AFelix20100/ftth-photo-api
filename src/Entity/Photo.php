<?php

namespace App\Entity;

use App\Controller\PhotoByInternalCommandController;
use App\Controller\PhotoByOperationController;
use DateTimeZone;
use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Event\PrePersistEventArgs;
use App\Controller\PhotoByServiceController;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/photos/commande-interne",
            controller: PhotoByInternalCommandController::class,
            openapiContext: [
                'summary' => 'Récupération des photos associées à une commande interne',
                'description' => 'Cette opération permet de récupérer toutes les photos liées à une commande interne spécifique. En fournissant la référence unique de la commande interne dans la requête, vous recevrez une liste complète des photos associées à cette commande. Cette fonctionnalité est utile pour visualiser ou gérer les documents photographiques enregistrés pour une commande particulière.',
                'parameters' => [
                    [
                        'name' => 'referenceCommandeInterne',
                        'in' => 'query',
                        'description' => 'La référence unique de la commande interne utilisée pour filtrer et récupérer les photos associées. Assurez-vous que la référence est correctement spécifiée pour obtenir les résultats attendus.',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'example' => 'CMD-INT-2024-XXXXXXXXXX',
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Liste des photos associées à la commande interne spécifiée.',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => [
                                                'type' => 'integer',
                                                'description' => 'L\'identifiant unique de la photo.',
                                                'example' => 1,
                                            ],
                                            'filePath' => [
                                                'type' => 'string',
                                                'description' => 'Le chemin d\'accès au fichier de la photo.',
                                                'example' => '/img/photo_1.jpg',
                                            ],
                                            'createdAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => 'La date et l\'heure de création de la photo au format ISO 8601.',
                                                'example' => '2024-08-13T14:52:00',
                                            ],
                                            'updatedAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => 'La date et l\'heure de la dernière mise à jour de la photo au format ISO 8601.',
                                                'example' => '2024-08-13T15:02:00',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Requête invalide en raison d\'une référence de commande interne manquante ou incorrecte.',
                    ],
                    '404' => [
                        'description' => 'Aucune photo trouvée pour la référence de commande interne spécifiée.',
                    ],
                ],
            ],
        ),
    ]
)]


#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/photos/prestation",
            controller: PhotoByServiceController::class,
            openapiContext: [
                'summary' => 'Récupération des photos associées à une prestation',
                'description' => 'Cette opération permet de récupérer toutes les photos liées à une prestation spécifique. En fournissant la référence unique de la prestation dans la requête, vous recevrez une liste complète des photos associées à cette prestation. Cette fonctionnalité est utile pour gérer ou visualiser les photos enregistrées pour une prestation particulière.',
                'parameters' => [
                    [
                        'name' => 'referencePrestation',
                        'in' => 'query',
                        'description' => 'La référence unique de la prestation utilisée pour filtrer et récupérer les photos associées. Assurez-vous que la référence est correctement spécifiée pour obtenir les résultats attendus.',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'example' => 'PREST-2024-XXXXXXXXXX',
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Liste des photos associées à la prestation spécifiée.',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => [
                                                'type' => 'integer',
                                                'description' => 'L\'identifiant unique de la photo.',
                                                'example' => 1,
                                            ],
                                            'filePath' => [
                                                'type' => 'string',
                                                'description' => 'Le chemin d\'accès au fichier de la photo.',
                                                'example' => '/img/photo_2.jpg',
                                            ],
                                            'createdAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => 'La date et l\'heure de création de la photo au format ISO 8601.',
                                                'example' => '2024-08-13T14:52:00',
                                            ],
                                            'updatedAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => 'La date et l\'heure de la dernière mise à jour de la photo au format ISO 8601.',
                                                'example' => '2024-08-13T15:02:00',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => 'Requête invalide en raison d\'une référence de prestation manquante ou incorrecte.',
                    ],
                    '404' => [
                        'description' => 'Aucune photo trouvée pour la référence de prestation spécifiée.',
                    ],
                ],
            ],
        ),
    ]
)]


#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/photos/intervention",
            controller: PhotoByOperationController::class,
            openapiContext: [
                'summary' => "Récupération des photos associées à une intervention",
                'description' => "Cette opération permet de récupérer toutes les photos liées à une intervention spécifique en utilisant sa référence unique. En fournissant la référence d'intervention dans la requête, vous recevrez une liste complète des photos associées à cette intervention. Chaque photo dans la liste est décrite par son identifiant, le chemin d'accès au fichier, ainsi que les dates de création et de dernière mise à jour.",
                'parameters' => [
                    [
                        'name' => 'referenceIntervention',
                        'in' => 'query',
                        'description' => "La référence unique de l'intervention pour filtrer et récupérer les photos associées. Cette référence doit être fournie dans la requête pour obtenir les résultats correspondants.",
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'example' => 'INTERV-2024-XXXXXXXXXX',
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => "Liste des photos associées à l'intervention spécifiée. Chaque photo est représentée par un objet contenant les informations suivantes :",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => [
                                                'type' => 'integer',
                                                'description' => "L'identifiant unique de la photo.",
                                                'example' => 1,
                                            ],
                                            'filePath' => [
                                                'type' => 'string',
                                                'description' => "Le chemin d'accès au fichier de la photo.",
                                                'example' => '/img/photo_1.jpg',
                                            ],
                                            'createdAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => "La date et l'heure de création de la photo au format ISO 8601.",
                                                'example' => '2024-08-13T14:52:00',
                                            ],
                                            'updatedAt' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                                'description' => "La date et l'heure de la dernière mise à jour de la photo au format ISO 8601.",
                                                'example' => '2024-08-13T15:02:00',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '400' => [
                        'description' => "Requête invalide en raison d'une référence d'intervention manquante ou incorrecte.",
                    ],
                    '404' => [
                        'description' => "Aucune photo trouvée pour la référence d'intervention spécifiée.",
                    ],
                ],
            ],
        ),
    ]
)]

class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Command $command = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Operation $operation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[PrePersist]
    public function setCreatedAtOnCreate(PrePersistEventArgs $eventArgs): void
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[PreUpdate]
    public function setTimestampsOnUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): static
    {
        $this->command = $command;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
