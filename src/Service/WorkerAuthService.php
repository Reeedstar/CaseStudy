<?php
namespace App\Service;

use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class WorkerAuthService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Function for authenticating the worker
    public function authenticate(Request $request): Worker
    {
       
        $workerId = $request->headers->get('x-worker-id');
        $workerToken = $request->headers->get('x-worker-token');

        if (!$workerId || !$workerToken) {
            throw new UnauthorizedHttpException('Bearer', 'Worker ID or Token is missing');
        }

        
        $worker = $this->entityManager->getRepository(Worker::class)->find($workerId);

        if (!$worker) {
            throw new UnauthorizedHttpException('Bearer', 'Worker not found');
        }

        // Here we can also include a query that asks whether the worker is active. 
        // if($worker->getActive())
        
        if ($worker->getAccessToken() !== $workerToken) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid Worker Token');
        }

        return $worker;
    }
}