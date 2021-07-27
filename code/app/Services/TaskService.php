<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\TaskStrategy;
use Exception;
use Illuminate\Http\Request;
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

    public function getTasks(int $userId): array
    {
        $userRole = $this->users->getUserRole($userId);
        if(!$userRole) {
            throw new Exception('Invalid User', 404);
        }
        $tasks = $this->factory->getUserFactory($this->tasks, $userRole->role_name);
        return $tasks->getTasks($userId);
    }

    public function addTask(string $summary, int $userId): Task
    {
        $getUser = $this->users->getUser($userId);
        if (!$getUser) {
            throw new Exception("Invalid User", 404);
        }
        $task = $this->tasks->addTask($summary, $userId);
        $this->logTask($getUser, $task);
        return $task;
    }

    protected function logTask(\stdClass $getUser, Task $task): void
    {
        Log::channel('tasks')
            ->info("The tech {$getUser->name} performed the task {$task->summary} on {$task->created_at}");
    }
}
