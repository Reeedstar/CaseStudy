<?php

namespace App\Entity;

use App\Entity\Enum\TaskStatus;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Serializer\Groups(["task"])]
    private ?string $id = null;

    #[ORM\Column]
    #[Serializer\Groups(["task"])]
    private string $status;

    #[ORM\Column]
    #[Serializer\Groups(["task"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Serializer\Groups(["task"])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Worker::class)]
    #[ORM\JoinColumn(name: "worker_id", referencedColumnName: "id", nullable: true)]
    #[Serializer\Groups(["task"])]
    private ?Worker $worker;

    /**
     * @var Collection<int, Alert>
     */
    #[ORM\OneToMany(targetEntity: Alert::class, mappedBy: 'task')]
    private Collection $alerts;

    public function __construct()
    {   $status = TaskStatus::NEW;
        $this->status = $status->name;
        $this->createdAt = new DateTimeImmutable();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getName(): ?string
    {
        return $this->name; 
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getWorker(): ?Worker
    {
        return $this->worker;
    }

    public function setWorker(Worker $worker): void
    {
        $this->worker = $worker;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setTask($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getTask() === $this) {
                $alert->setTask(null);
            }
        }

        return $this;
    }
}
