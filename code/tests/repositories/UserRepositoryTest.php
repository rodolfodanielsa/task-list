<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use DeepCopy\Reflection\ReflectionHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGetUserRole()
    {
        $usersModel = $this->createMock(User::class);
        $roleModel = $this->createMock(Role::class);

        $repo = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$usersModel])
            ->onlyMethods(['getUser'])
            ->getMock();

        $repo->method('getUser')
            ->willReturn($roleModel);

        $result = $repo->getUserRole(1);

        $this->assertEquals($roleModel, $result);
    }
}
