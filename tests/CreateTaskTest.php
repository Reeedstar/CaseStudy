<?php 
use App\Entity\Task;
use PHPUnit\Framework\TestCase;

final class CreateTaskTest extends TestCase
{
    public function testCreateAlert(): void
    {
        $task = new Task();

        $this->assertInstanceOf(Task::class, $task);
    }
}