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
use App\Repository\CommandeRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
// #[ApiResource]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $referencePrestation = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceCommandeInterne = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'commande')]
    private Collection $photos;

    

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->referencePrestation = $this->getReferences()["PRESTATION"];
        $this->referenceCommandeInterne = $this->getReferences()["COMMANDE_INTERNE"];
        
    }

    #[Assert\Callback]
    public function validateReferences(ExecutionContextInterface $context): void
    {
        if (empty($this->referencePrestation) || empty($this->referenceCommandeInterne)) {
            $context->buildViolation('Either referencePrestation or referenceCommandeInterne must be provided.')
                ->atPath('referencePrestation')
                ->addViolation();

            $context->buildViolation('Either referencePrestation or referenceCommandeInterne must be provided.')
                ->atPath('referenceCommandeInterne')
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
            $photo->setCommande($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCommande() === $this) {
                $photo->setCommande(null);
            }
        }

        return $this;
    }

    public function getReferencePrestation(): ?string
    {
        return $this->referencePrestation;
    }

    public function setReferencePrestation(string $referencePrestation): static
    {
        $this->referencePrestation = $referencePrestation;

        return $this;
    }

    public function getReferenceCommandeInterne(): ?string
    {
        return $this->referenceCommandeInterne;
    }

    public function setReferenceCommandeInterne(string $referenceCommandeInterne): static
    {
        $this->referenceCommandeInterne = $referenceCommandeInterne;

        return $this;
    }

    private function getReferences(): array
    {
        $prefixPrestation = "PREST";
        $prefixCommandeInterne = "CMD-INT";

        $year = date('Y');

        // Générer un code alphanumérique unique de 10 caractères
        $code = $this->generateUniqueCode();

        // Formater le numéro de prestation
        return 
        [
            "PRESTATION" => sprintf('%s-%s-%s', $prefixPrestation, $year, $code),
            "COMMANDE_INTERNE" => sprintf('%s-%s-%s', $prefixCommandeInterne, $year, $code)
        ];
            
    }

    private function generateUniqueCode(int $length = 10): string
    {
        // Générer un code alphanumérique unique de la longueur spécifiée
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
    }
}
