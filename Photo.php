<?php

namespace App\Entity;

use DateTimeZone;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ApiResource]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referencePrestation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commandeInterne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $intervention = null;

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

    #[Assert\Callback]
    public function validateUniqueReference(ExecutionContextInterface $context)
    {
        $fields = [
            'referencePrestation' => $this->referencePrestation,
            'commandeInterne' => $this->commandeInterne,
            'intervention' => $this->intervention,
        ];

        $nonNullFields = array_filter($fields, fn($value) => !is_null($value));

        if (count($nonNullFields) > 1) {
            $context->buildViolation('Only one of referencePrestation, commandeInterne, or intervention can be non-null.')
                ->atPath('referencePrestation')
                ->addViolation();
        }

        if (count($nonNullFields) === 0) {
            $context->buildViolation('At least one of referencePrestation, commandeInterne, or intervention must be non-null.')
                ->atPath('referencePrestation')
                ->addViolation();
        }
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

    public function getReferencePrestation(): ?string
    {
        return $this->referencePrestation;
    }

    public function setReferencePrestation(?string $referencePrestation): static
    {
        $this->referencePrestation = $referencePrestation;

        return $this;
    }

    public function getCommandeInterne(): ?string
    {
        return $this->commandeInterne;
    }

    public function setCommandeInterne(?string $commandeInterne): static
    {
        $this->commandeInterne = $commandeInterne;

        return $this;
    }

    public function getIntervention(): ?string
    {
        return $this->intervention;
    }

    public function setIntervention(?string $intervention): static
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
