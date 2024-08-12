<?php

namespace App\Entity;

use DateTimeZone;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
// use ApiPlatform\Metadata\ApiResource;
use App\Repository\InterventionRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
// #[ApiResource]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroIntervention = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'intervention')]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->numeroIntervention = $this->getReference();
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

    public function getNumeroIntervention(): ?string
    {
        return $this->numeroIntervention;
    }

    public function setNumeroIntervention(string $numeroIntervention): static
    {
        $this->numeroIntervention = $numeroIntervention;

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
            $photo->setIntervention($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getIntervention() === $this) {
                $photo->setIntervention(null);
            }
        }

        return $this;
    }

    private function getReference(): string
    {
        $prefixPrestation = "INT";
        $year = date('Y');

        // Générer un code alphanumérique unique de 10 caractères
        $code = $this->generateUniqueCode();

        // Formater le numéro de prestation
        return sprintf('%s-%s-%s', $prefixPrestation, $year, $code);
            
    }

    private function generateUniqueCode(int $length = 10): string
    {
        // Générer un code alphanumérique unique de la longueur spécifiée
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
    }
}
