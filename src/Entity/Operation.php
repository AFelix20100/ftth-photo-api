<?php

namespace App\Entity;

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
use App\Controller\PhotoByOperationController;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(identifier: true)]
    private ?string $operationReference = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
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
