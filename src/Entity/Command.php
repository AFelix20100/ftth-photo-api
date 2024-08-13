<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DateTimeZone;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\CommandRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/commande/{id}',
            requirements: ['id' => '\d+'],
            openapiContext: [
                'summary' => 'Récupérer une commande spécifique',
                'description' => 'Obtenez les détails d’une commande spécifique en utilisant son ID. Renvoie des informations détaillées sur la commande.',
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
            ]
        ),
        new GetCollection(
            uriTemplate: '/commandes',
            openapiContext: [
                'summary' => 'Récupérer la liste des commandes',
                'description' => 'Obtenez toutes les commandes du système. Cette opération supporte la pagination',
            ]
        ),
        new Post(
            uriTemplate: '/commande',
            openapiContext: [
                'summary' => 'Créer une nouvelle commande',
                'description' => 'Soumettez une nouvelle commande au système. Fournissez tous les détails nécessaires pour la création de la commande.',
            ],
            normalizationContext: ['groups' => ['command:read']],
            denormalizationContext: ['groups' => ['command:write']],
        ),
        new Patch(
            uriTemplate: '/commande/{id}',
            openapiContext: [
                'summary' => 'Mettre à jour partiellement une commande',
                'description' => 'Cette opération permet de mettre à jour certaines propriétés d\'une commande existante en utilisant son ID.',
            ],
            normalizationContext: ['groups' => ['command:read']],
            denormalizationContext: ['groups' => ['command:patch']],
        ),
        new Delete(
            uriTemplate: 'commande/{id}',
            requirements: ['id' => '\d+'],
            openapiContext: [
                'summary' => 'Supprimer une commande',
                'description' => 'Supprimez une commande du système en utilisant son ID. Cette action est irréversible.',
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
            ]
        ),
    ]
)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['command:read'])]
    private ?string $serviceReference = null;

    #[ORM\Column(length: 255)]
    #[Groups(['command:read'])]
    private ?string $internalCommandReference = null;

    #[ORM\Column]
    #[Groups(['command:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['command:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'command', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $photos;

    #[ORM\Column(length: 255)]
    #[Groups(['command:write', 'command:read', 'command:patch'])]
    private ?string $description = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $references = $this->getReferences();
        $this->serviceReference = $references["SERVICE"];
        $this->internalCommandReference = $references["INTERNAL_COMMAND"];
        
    }

    #[Assert\Callback]
    public function validateReferences(ExecutionContextInterface $context): void
    {
        if (empty($this->serviceReference) || empty($this->internalCommandReference)) {
            $context->buildViolation('Either serviceReference or internalCommandReference must be provided.')
                ->atPath('serviceReference')
                ->addViolation();

            $context->buildViolation('Either serviceReference or internalCommandReference must be provided.')
                ->atPath('internalCommandReference')
                ->addViolation();
        }
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
            $photo->setCommand($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCommand() === $this) {
                $photo->setCommand(null);
            }
        }

        return $this;
    }

    public function getServiceReference(): ?string
    {
        return $this->serviceReference;
    }

    public function setServiceReference(string $serviceReference): static
    {
        $this->serviceReference = $serviceReference;

        return $this;
    }

    public function getInternalCommandReference(): ?string
    {
        return $this->internalCommandReference;
    }

    public function setInternalCommandReference(string $internalCommandReference): static
    {
        $this->internalCommandReference = $internalCommandReference;

        return $this;
    }

    private function getReferences(): array
    {
        $prefixService = "PREST";
        $prefixInternalCommand = "CMD-INT";

        $year = date('Y');

        // Generate a unique 10-character alphanumeric code
        $code = $this->generateUniqueCode();

        // Format the reference numbers
        return [
            "SERVICE" => sprintf('%s-%s-%s', $prefixService, $year, strtoupper($code)),
            "INTERNAL_COMMAND" => sprintf('%s-%s-%s', $prefixInternalCommand, $year, strtoupper($code))
        ];
    }


    private function generateUniqueCode(int $length = 10): string
    {
        // Generate a unique alphanumeric code of the specified length
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
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

}
