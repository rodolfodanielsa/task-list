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
    protected Task $task;

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

    public function addTask(Request $request, int $userId): Task
    {
        $this->task->fill([
            'summary' => $request->input('summary'),
            'user_id' => $userId,
        ]);

        $this->task->save();
        return $this->task;
    }
}
