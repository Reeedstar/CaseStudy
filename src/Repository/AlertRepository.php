<?php

namespace App\Repository;

use App\Entity\Alert;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Alert>
 */
class AlertRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Alert::class);
        $this->entityManager = $em;
    }

    public function createOrUpdateAlert(string $taskId, string $alertType, string $message, string $reason): void
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
    
        if (!$task) {
            throw new \InvalidArgumentException('No Task Found');
        }
        

        $alert = $this->entityManager->getRepository(Alert::class)->findOneBy(["task" => $task]);

        if($alert)
        {
            $alert->setLastSeenAt(new \DateTimeImmutable());
            $alert->setAlertType($alertType);
            $this->entityManager->persist($alert);
        } else {
            $alert = new Alert($message, $reason, $task, $alertType);
            $this->entityManager->persist($alert);
        }

        $this->entityManager->flush();
    }

    public function resolveAlert(Task $task): void
    {
        $alert = $this->entityManager->getRepository(Alert::class)->findOneBy(["task"=> $task]);
        if ($alert) {

            $alert->setResolved(true);

            $this->entityManager->persist($alert);
            $this->entityManager->flush();   
        }
    }
}
