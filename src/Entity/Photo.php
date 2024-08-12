<?php

namespace App\Entity;

use DateTimeZone;
use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\PhotoByPrestationController;
use Doctrine\ORM\Event\PrePersistEventArgs;
use App\Controller\PhotoByReferenceController;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        name: "get_photos_by_reference",
        uriTemplate: "/photos/by-reference",
        controller: PhotoByReferenceController::class,
        openapiContext: [
            'summary' => 'Retrieve photos by referencePrestation or referenceCommandeInterne',
            'parameters' => [
                [
                    'name' => 'referenceCommandeInterne',
                    'in' => 'query',
                    'description' => 'The referenceCommandeInterne to filter photos by.',
                    'required' => false,
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                [
                    'name' => 'referencePrestation',
                    'in' => 'query',
                    'description' => 'The referencePrestation to filter photos by.',
                    'required' => false,
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ),
])]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Intervention $intervention = null;

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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getIntervention(): ?Intervention
    {
        return $this->intervention;
    }

    public function setIntervention(?Intervention $intervention): static
    {
        $this->intervention = $intervention;

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
