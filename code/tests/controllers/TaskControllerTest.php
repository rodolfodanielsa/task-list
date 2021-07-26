<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskControllerTest extends TestCase
{
    /** @var TaskService|MockObject */
    protected $service;
    /** @var Request|MockObject */
    protected $request;
    protected TaskController $controller;

    public function setUp(): void
    {
        $this->service = $this->createMock(TaskService::class);
        $this->controller = new TaskController($this->service);
    }
    public function testShow(): void
    {
        $collection = new Collection([]);
        $response = new JsonResponse($collection, 200);

        $userId = 1;
        $this->service->expects($this->once())
            ->method('getTasks')
            ->with($userId)
            ->willReturn($collection);
        $result = $this->controller->show($userId);
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals([], $result->getData());
    }

    public function testStore(): void
    {
        $task = new Task();
        $response = new JsonResponse($task, 200);
        $request = new Request();

        $userId = 1;
        $this->service->expects($this->once())
            ->method('addTask')
            ->with($request, $userId)
            ->willReturn($task);

        $result = $this->controller->store($request, $userId);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals([], $result->getData());
    }
}
