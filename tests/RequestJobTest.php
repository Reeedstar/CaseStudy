<?php 
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class requestJobTest extends WebTestCase
{
    public function testRequestJob(): void
    {
        $client = static::createClient();

        $client->request("GET","api/task/request-task", [], [],  ['HTTP_X_WORKER_ID' => '01933b35-32d9-7546-aba7-e9e32a5738c1', 'HTTP_X_WORKER_TOKEN' => '758a23c893b2e69eef2477936cba2aa002c65d52738a001e9879c4b7b6c7b0e5']);

        $this->assertResponseIsSuccessful();
    }
}