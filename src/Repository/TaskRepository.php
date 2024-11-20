<?php

namespace App\Repository;

use App\Entity\Enum\TaskStatus;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTaskForWorker(): ?Task
    {
        // added max results
        $task = $this->createQueryBuilder('t')           
        ->where('t.status = :status')  
        ->setParameter('status', 'NEW') 
        ->orderBy('t.createdAt', 'DESC')
        ->getQuery()
        ->setMaxResults(1)
        ->getOneOrNullResult();

        if (!$task) {
            return null;
        }

        return $task;
    }
}
