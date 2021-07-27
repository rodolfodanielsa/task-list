<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Exception;
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

    /**
     * @dataProvider provideShow
     */
    public function testShow(int $userId, array $expected, int $calls, ?array $exception = null): void
    {
        if ($exception) {
            $statusCode = $exception['code'];
            $this->service->expects($this->once())
                ->method('getTasks')
                ->with($userId)
                ->willThrowException(new $exception['type']($exception['message'], $exception['code']));
        } else {
            $statusCode = 200;
            $this->service->expects($this->once())
                ->method('getTasks')
                ->with($userId)
                ->willReturn($expected);
        }

        $result = $this->controller->show($userId);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($statusCode, $result->getStatusCode());
        $this->assertEquals($expected, $result->getData(true));

    }

    public function testStore(): void
    {

    }

    public function provideShow(): array
    {
        return [
            'user does not have tasks' => [
                'userId' => 1,
                'expected' => [],
                'calls' => 1,
            ],
            'user has tasks' => [
                'userId' => 2,
                'expected' => [
                    'summary' => 'task',
                    'user_id' => 2,
                    'created_at' => '2021-07-26 12:34:56',
                ],
                'calls' => 1,
            ],
            'user does not exist' => [
                'userId' => 3,
                'expected' => [
                    'error' => 'Invalid User',
                ],
                'calls' => 0,
                'exception' => [
                    'type' => Exception::class,
                    'message' => 'Invalid User',
                    'code' => 404,
                ]
            ]
        ];
    }
}
