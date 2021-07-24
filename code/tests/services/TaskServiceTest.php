<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Models\Role;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\TaskStrategy;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{

    public function testGetTasks()
    {
        $userRepo = $this->createMock(UserRepository::class);
        $taskRepo = $this->createMock(TaskRepository::class);
        $taskStrategy = $this->createMock(TaskStrategy::class);
        $userFactory = $this->createMock(UserFactory::class);
        $collection = $this->createMock(Collection::class);
        $role = new Role();

        $role->setAttribute('role_name', 'roleName');

        $userRepo->expects($this->once())
            ->method('getUserRole')
            ->with(1)
            ->willReturn($role);

        $taskStrategy->expects($this->once())
            ->method('getTasks')
            ->with(1)
            ->willReturn($collection);

        $userFactory->expects($this->once())
            ->method('getUserFactory')
            ->with($taskRepo, $role->getAttribute('role_name'))
            ->willReturn($taskStrategy);

        $service = new TaskService($userRepo, $taskRepo, $userFactory);
        $result = $service->getTasks(1);

        $this->assertEquals($result, $collection);
    }
}
