<?php

namespace App\Command;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:generate-task',
)]
class GenerateTaskCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $task = new Task();
        $task->setName('Task created at '.(new DateTimeImmutable())->format('Y-m-d H:i:s'));
        $em = $this->entityManager;
        $em->persist($task);
        $em->flush();

        $io->success('Task created successfully!');

        return Command::SUCCESS;
    }
}
