<?php

namespace App\Controller;

use App\Entity\Enum\TaskStatus;
use App\Entity\Task;
use App\Entity\Alert;
use App\Repository\AlertRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\WorkerAuthService;
use App\Repository\TaskRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Rest\Route("/task")]
class TaskController extends AbstractController
{

    private WorkerAuthService $workerAuthService;

    /**
     * @var TaskRepository
     */
    private TaskRepository $taskRepository;

    /**
     * @var AlertRepository
     */
    private AlertRepository $alertRepository;

    public function __construct(WorkerAuthService $workerAuthService, TaskRepository $taskRepository, AlertRepository $alertRepository)
    {
        $this->workerAuthService = $workerAuthService;
        $this->taskRepository = $taskRepository;
        $this->alertRepository = $alertRepository;
    }

    #[Rest\Get("/request-task")]
    #[Rest\View(serializerGroups: ["task"])]
    public function requestJobAction(Request $request, EntityManagerInterface $em): ?string
    {
        $worker = $this->workerAuthService->authenticate($request);

        $task = $this->taskRepository->findTaskForWorker();
        
        if (!$task) {
            throw $this->createNotFoundException('No task found!');
        }

        $task->setStatus(TaskStatus::RUNNING->name);
        $task->setWorker($worker);

        $em->flush();

        return $task->getId();
    }

    #[Rest\Post('/{task_id}/{status}')]
    #[Rest\View(serializerGroups: ['task'])]
    public function finishJobAction(Request $request, EntityManagerInterface $em): void
    {

        $this->workerAuthService->authenticate($request);

        $taskId = $request->get('task_id');
        $status = $request->get('status');

        if (!isset($taskId, $status)) {
            throw new \InvalidArgumentException('Parameter missing');
        }

        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw $this->createNotFoundException('Task not found.');
        }

        if (!in_array($status, ['completed', 'failed'])) {
            throw new \InvalidArgumentException('Invalid status.');
        }

        if ($status === 'completed') {
            $task->setStatus(TaskStatus::FINISHED->name);
        } else {
            $task->setStatus(TaskStatus::FAILED->name);
        }

        $this->checkForAlert($task, $em);

        $em->persist($task);
        $em->flush();
    }

    private function checkForAlert(Task $task, EntityManagerInterface $em): void
    {
        $this->alertRepository->resolveAlert($task);
    }

}
