<?php

namespace App\Entity;

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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiFilter(SearchFilter::class)]
    private ?string $serviceReference = null;

    #[ORM\Column(length: 255)]
    #[ApiFilter(SearchFilter::class)]
    private ?string $internalCommandReference = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'command')]
    private Collection $photos;

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

}
