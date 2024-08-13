<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use DateTimeZone;
use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\OperationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/intervention/{id}',
            requirements: ['id' => '\d+'],
            openapiContext: [
                'summary' => 'Obtenir une intervention spécifique',
                'description' => 'Récupère les détails d\'une intervention en fonction de son ID.',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'L’identifiant unique de la commande.',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                            'example' => 1
                        ]
                    ],
                ],
            ],
        ),
        new GetCollection(
            uriTemplate: '/interventions',
            openapiContext: [
                'summary' => 'Lister toutes les interventions',
                'description' => 'Récupère une collection d\'interventions.',
            ],
        ),
        new Post(
            uriTemplate: '/intervention',
            openapiContext: [
                'summary' => 'Créer une nouvelle intervention',
                'description' => 'Créer une nouvelle intervention avec les détails fournis.',
            ],
            normalizationContext: ['groups' => ['intervention:read']],
            denormalizationContext: ['groups' => ['intervention:write']],
        ),
        new Patch(
            uriTemplate: '/intervention/{id}',
            openapiContext: [
                'summary' => 'Mettre à jour une intervention',
                'description' => 'Mise à jour partielle d\'une intervention existante.',
            ],
            normalizationContext: ['groups' => ['intervention:read']],
            denormalizationContext: ['groups' => ['intervention:patch']],
        ),

        new Delete(
            uriTemplate: '/intervention/{id}',
            openapiContext: [
                'summary' => 'Supprimer une intervention',
                'description' => 'Supprime une intervention en fonction de son ID.',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'L’identifiant unique de la commande à supprimer.',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                            'example' => 1
                        ]
                    ],
                ],
            ],
        )
    ]
)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['intervention:read'])]
    private ?string $operationReference = null;

    #[ORM\Column(length: 255)]
    #[Groups(['intervention:write', 'intervention:read', 'intervention:patch'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['intervention:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['intervention:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'operation')]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->operationReference = $this->getReference();
    }

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

    public function getOperationReference(): ?string
    {
        return $this->operationReference;
    }

    public function setOperationReference(string $operationReference): static
    {
        $this->operationReference = $operationReference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setOperation($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getOperation() === $this) {
                $photo->setOperation(null);
            }
        }

        return $this;
    }

    private function getReference(): string
    {
        $prefixIntervention = "INTERV";
        $year = date('Y');

        // Generate a unique alphanumeric code of 10 characters
        $uniqueCode = $this->generateUniqueCode();

        // Format the intervention reference number
        return sprintf('%s-%s-%s', $prefixIntervention, $year, strtoupper($uniqueCode));
    }

    private function generateUniqueCode(int $length = 10): string
    {
        // Generate a unique alphanumeric code of the specified length
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
    }

}
