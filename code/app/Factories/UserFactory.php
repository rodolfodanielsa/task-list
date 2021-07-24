<?php

namespace App\Factories;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\ManagerStrategy;
use App\Strategies\TaskStrategy;
use App\Strategies\TechnicianStrategy;

class UserFactory
{
    public function getUserFactory(TaskRepository $repository, string $role): TaskStrategy
    {
        if ($role === 'Manager') {
            return new ManagerStrategy($repository);
        }

        return new TechnicianStrategy($repository);
    }
}
