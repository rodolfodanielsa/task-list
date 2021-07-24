<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\TaskStrategy;
use Illuminate\Support\Collection;

class TaskService
{
    protected UserRepository $users;
    protected UserFactory $factory;
    protected TaskRepository $tasks;

    public function __construct(UserRepository $users, TaskRepository $tasks,UserFactory $factory)
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
}
