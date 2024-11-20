<?php

namespace App\Entity\Enum;

enum TaskStatus: string
{
    case NEW = 'new';
    case RUNNING = 'running';
    case FAILED = 'failed';
    case FINISHED = 'finished';
}
