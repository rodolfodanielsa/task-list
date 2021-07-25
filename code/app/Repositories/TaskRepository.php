<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;
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

    public function addTask(Request $request, int $userId)
    {
        $this->task->fill([
            'summary' => 'teste',
            'user_id' => $userId,
        ]);

        $this->task->save();
        return $this->task;
    }
}
