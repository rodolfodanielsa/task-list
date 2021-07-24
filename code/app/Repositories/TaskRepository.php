<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository
{
    /**
     * @var Task
     */
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getAllTasks(): Collection
    {
        return $this->task->get();
    }

    public function getTasksByUser(int $userId): Collection
    {
        return $this->task->where('user_id', $userId)->get();
    }
}
