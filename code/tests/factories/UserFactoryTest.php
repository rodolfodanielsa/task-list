<?php

namespace App\Factories;

use App\Repositories\TaskRepository;
use App\Strategies\ManagerStrategy;
use App\Strategies\TechnicianStrategy;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{

    /**
     * @dataProvider providerGetUserFactory
     */
    public function testGetUserFactory(string $role, string $expected)
    {
        $taskRepo = $this->createMock(TaskRepository::class);
        $factory = new UserFactory();
        $result = $factory->getUserFactory($taskRepo, $role);
        $this->assertInstanceOf($expected, $result);
    }

    public function providerGetUserFactory(): array
    {
        return [
            'role is Technician' => [
                'role' => 'Technician',
                'expects' => TechnicianStrategy::class,
            ],
            'role is Plumber' => [
                'role' => 'Plumber',
                'expects' => TechnicianStrategy::class,
            ],
            'role is Manager' => [
                'role' => 'Manager',
                'expects' => ManagerStrategy::class,
            ]
        ];
    }
}
