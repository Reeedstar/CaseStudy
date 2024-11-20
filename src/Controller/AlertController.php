<?php

namespace App\Controller;


use App\Entity\Alert;
use App\Repository\AlertRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\WorkerAuthService;

class AlertController extends AbstractController
{

    /**
     * @var AlertRepository
     */
    private AlertRepository $alertRepository;

    private WorkerAuthService $workerAuthService;

    public function __construct(AlertRepository $alertRepository, WorkerAuthService $workerAuthService)
    {
        $this->alertRepository = $alertRepository;
        $this->workerAuthService = $workerAuthService;
    }

    #[Rest\Post('/alerts')]
    #[Rest\View(serializerGroups: ['alerts'])]
    public function alertAction(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->workerAuthService->authenticate($request);

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['task_id'], $data['alert_type'], $data['message'], $data['reason'])) {
            return new JsonResponse(['error' => 'Parameter missing'], 400);
        }

        $taskId = $data['task_id'];
        $alert_type = $data['alert_type'];
        $message = $data['message'];
        $reason = $data['reason'];

        if (!in_array($alert_type, ['error', 'warning'], true)) {
            throw new \InvalidArgumentException('Invalid status value.');
        }

        $this->alertRepository->createOrUpdateAlert($taskId, $alert_type, $message, $reason);

        return $this->json(['message' => 'Alert created or updated']);

    }

}
