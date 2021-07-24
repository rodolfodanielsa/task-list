<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TaskControllerTest extends TestCase
{

    public function testShow()
    {
        $service = $this->createMock(TaskService::class);
        $request = $this->createMock(Request::class);
        $controller = new TaskController($service);
        $collection = new Collection([]);
        $response = new JsonResponse($collection, 200, []);

        $userId = 1;
        $service->expects($this->once())
            ->method('getTasks')
            ->with($userId)
            ->willReturn($collection);
        $result = $controller->show($request, $userId);
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals([], $result->getData());
    }
}
