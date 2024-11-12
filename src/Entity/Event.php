<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $eventName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $registrationLimit = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotBlank(message: 'Event date cannot be empty.')]
    private ?\DateTimeInterface $eventDate = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'event')]
    private Collection $registeredUsers;

    public function __construct()
    {
        $this->registeredUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

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

    public function getRegistrationLimit(): ?int
    {
        return $this->registrationLimit;
    }

    public function setRegistrationLimit(int $registrationLimit): self
    {
        $this->registrationLimit = $registrationLimit;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRegisteredUsers(): Collection
    {
        return $this->registeredUsers;
    }

    public function addRegisteredUser(User $registeredUser): static
    {
        if (!$this->registeredUsers->contains($registeredUser)) {
            $this->registeredUsers->add($registeredUser);
            $registeredUser->setEvent($this);
        }

        return $this;
    }

    public function removeRegisteredUser(User $registeredUser): self
    {
        if ($this->registeredUsers->removeElement($registeredUser)) {
            // set the owning side to null (unless already changed)
            if ($registeredUser->getEvent() === $this) {
                $registeredUser->setEvent(null);
            }
        }

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    public function isPassed(): bool
    {
        return $this->eventDate !== null && $this->eventDate < new \DateTime();
    }

    public function getRegistrationsLeft(): int
    {
        $registeredCount = count($this->registeredUsers);
        $registrationLimit = $this->registrationLimit ?? 0;

        return $registrationLimit - $registeredCount;
    }


}
