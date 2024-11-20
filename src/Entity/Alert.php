<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
class Alert
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Serializer\Groups(["alert"])]
    private ?int $id;

    #[ORM\ManyToOne(inversedBy: 'alerts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $lastSeenAt = null;

    #[ORM\Column]
    private ?bool $resolved = null;

    #[ORM\Column(length: 255)]
    private ?string $alertType = null;

    public function __construct(string $message, string $reason, Task $task, string $alertType)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->lastSeenAt = new DateTimeImmutable();
        $this->resolved = false;
        $this->message = $message;
        $this->reason = $reason;
        $this->task = $task;
        $this->alertType = $alertType;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastSeenAt(): ?DateTimeImmutable
    {
        return $this->lastSeenAt;
    }

    public function setLastSeenAt(DateTimeImmutable $lastSeenAt): static
    {
        $this->lastSeenAt = $lastSeenAt;

        return $this;
    }

    public function isResolved(): ?bool
    {
        return $this->resolved;
    }

    public function setResolved(bool $resolved): static
    {
        $this->resolved = $resolved;

        return $this;
    }

    public function getAlertType(): ?string
    {
        return $this->alertType;
    }

    public function setAlertType(string $alertType): static
    {
        $this->alertType = $alertType;
        return $this;
    }
}
