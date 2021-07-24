<?php

namespace App\Strategies;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class TechnicianStrategy implements TaskStrategy
{
    /**
     * @var TaskRepository
     */
    protected $task;

    public function __construct(TaskRepository $task)
    {
        $this->task = $task;
    }

    public function getTasks(int $userId): Collection
    {
        return $this->task->getTasksByUser($userId);
    }
}
