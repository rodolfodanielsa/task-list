<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\TaskStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TaskService
{
    protected UserRepository $users;
    protected UserFactory $factory;
    protected TaskRepository $tasks;

    public function __construct(UserRepository $users, TaskRepository $tasks, UserFactory $factory)
    {
        $this->tasks = $tasks;
        $this->users = $users;
        $this->factory = $factory;
    }

    public function getTasks(int $userId): Collection
    {
        $tasks = $this->factory->getUserFactory($this->tasks, $this->users->getUserRole($userId)->role_name);
        return $tasks->getTasks($userId);
    }

    public function addTask(Request $request, int $userId): Task
    {
        $task = $this->tasks->addTask($request, $userId);
        Log::channel('tasks')
            ->info("The tech {$task->user_id} performed the task {$task->summary} on date {$task->created_at}");
        return $task;
    }
}
