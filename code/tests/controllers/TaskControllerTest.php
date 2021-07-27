<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * @dataProvider provideStore
     */
    public function testStore(
        array $requestBody,
        int $userId,
        bool $fails,
        int $addTaskCount,
        array $expected,
        int $statusCode,
        ?array $exception = null
    ): void {
        $controller = $this->getMockBuilder(TaskController::class)
            ->setConstructorArgs([$this->service])
            ->onlyMethods(['validateRequest'])
            ->getMock();
        $validator = $this->createMock(Validator::class);
        $request = new Request($requestBody);

        $addTask = $this->service->expects($this->exactly($addTaskCount))
            ->method('addTask')
            ->with($request->input('summary'), $userId);

        if ($exception) {
            $addTask->willThrowException(new $exception['type']($exception['message'], $exception['code']));
        } else {
            $addTask->willReturn(new Task());
        }

        $validator->expects($this->once())
            ->method('fails')
            ->willReturn($fails);

        $validator->expects($this->exactly((int) $fails))
            ->method('getMessageBag')
            ->willReturn($expected);

        $controller->expects($this->once())
            ->method('validateRequest')
            ->willReturn($validator);

        $result = $controller->store($request, $userId);

        $this->assertEquals($statusCode, $result->getStatusCode());
        $this->assertEquals($expected, (array)$result->getData());
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

    public function provideStore(): array
    {
        return [
            'Task added successfully' => [
                'requestBody' => ['summary' => '111'],
                'userId' => 1,
                'fails' => false,
                'addTaskCount' => 1,
                'expected' => [],
                'statusCode' => 200,
            ],
            'Validator fails' => [
                'requestBody' => ['summary' => ''],
                'userId' => 1,
                'fails' => true,
                'addTaskCount' => 0,
                'expected' => ["summary" => "The summary field is required."],
                'statusCode' => 400,
            ],
            'Task not added' => [
                'requestBody' => ['summary' => 'asdasd'],
                'userId' => 1,
                'fails' => false,
                'addTaskCount' => 1,
                'expected' => ["error" => "Database Error"],
                'statusCode' => 500,
                'exception' => [
                    'type' => \Exception::class,
                    'message' => 'Database Error',
                    'code' => 500,
                ],
            ],
        ];
    }
}
