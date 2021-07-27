<?php

namespace App\Strategies;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class ManagerStrategy implements TaskStrategy
{
    protected TaskRepository $task;

    public function __construct(TaskRepository $task)
    {
        $this->task = $task;
    }

    public function getTasks(int $userId = 0): array
    {
        return $this->task->getAllTasks();
    }
}
