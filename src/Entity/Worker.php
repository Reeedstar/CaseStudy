<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Serializer\Groups(["worker"])]
    private ?string $id = null;

    #[ORM\Column]
    #[Serializer\Groups(["worker"])]
    private bool $active = false;

    #[ORM\Column(nullable: false)]
    private string $accessToken;

    public function __construct()
    {
        $this->accessToken = bin2hex(random_bytes(32));
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getActive():bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }



}
