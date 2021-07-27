<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{

    public function testIndex()
    {
        $service = $this->createMock(UserService::class);

        $controller = new UserController($service);

        $service->expects($this->once())
            ->method('getUsers')
            ->willReturn([]);

        $result = $controller->index();
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
