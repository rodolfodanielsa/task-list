<?php

namespace App\Services;

use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{

    public function testGetUsers()
    {
        $repo = $this->createMock(UserRepository::class);
        $repo->method('getAllUsers')
            ->willReturn([]);

        $service = new UserService($repo);
        $result = $service->getUsers();
        $this->assertIsArray($result);
    }
}
