<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Models\Role;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Strategies\TaskStrategy;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class TaskServiceTest extends TestCase
{
    /**
     * @var UserRepository|MockObject
     */
    protected $userRepo;
    /**
     * @var TaskRepository|MockObject
     */
    protected $taskRepo;
    /**
     * @var TaskStrategy|MockObject
     */
    protected $taskStrategy;
    /**
     * @var UserFactory|MockObject
     */
    protected $userFactory;

    public function setup(): void
    {
        $this->userRepo = $this->createMock(UserRepository::class);
        $this->taskRepo = $this->createMock(TaskRepository::class);
        $this->taskStrategy = $this->createMock(TaskStrategy::class);
        $this->userFactory = $this->createMock(UserFactory::class);
    }

    /**
     * @dataProvider provideGetTasks
     */
    public function testGetTasks(array $willReturnTasks, ?stdClass $willReturnRole, int $callsFactory, ?array $exception = null)
    {
        $this->userRepo->expects($this->once())
            ->method('getUserRole')
            ->with($willReturnTasks['user_id'])
            ->willReturn($willReturnRole);

        $this->taskStrategy->expects($this->exactly($callsFactory))
            ->method('getTasks')
            ->with($willReturnTasks['user_id'])
            ->willReturn($willReturnTasks);

        $userFactoryAssert = $this->userFactory->expects($this->exactly($callsFactory))
            ->method('getUserFactory');

        if (!$exception) {
            $userFactoryAssert
                ->with($this->taskRepo, $willReturnRole->role_name)
                ->willReturn($this->taskStrategy);
        }

        $service = new TaskService($this->userRepo, $this->taskRepo, $this->userFactory);

        if ($exception) {
            $this->expectException($exception['type']);
            $this->expectErrorMessage($exception['message']);
            $this->expectExceptionCode($exception['code']);
        }
        $result = $service->getTasks($willReturnTasks['user_id']);

        $this->assertEquals($result, $willReturnTasks);
    }

    public function provideGetTasks(): array
    {
        return [
            'user role exists' => [
                'willReturnTasks' => [
                    'summary' => 'xyz',
                    'user_id' => 1,
                ],
                'willReturnRole' => (object)[
                    'some' => "role",
                    "role_name" => "Tech",
                ],
                'callsFactory' => 1,
            ],
            'user role does not exist' => [
                'willReturnTasks' => [
                    'summary' => 'xyz',
                    'user_id' => 1,
                ],
                'willReturnRole' => null,
                'callsFactory' => 0,
                'exception' => [
                    'type' => \Exception::class,
                    'message' => 'Invalid User',
                    'code' => 404,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideAddTask
     */
    public function testAddTask(int $userId, string $summary, ?stdClass $user, int $addTaskCalls, ?array $exception = null)
    {
        $this->userRepo->expects($this->once())
            ->method('getUser')
            ->with($userId)
            ->willReturn($user);

        $service = $this->getMockBuilder(TaskService::class)
            ->setConstructorArgs([$this->userRepo, $this->taskRepo, $this->userFactory])
            ->onlyMethods(['logTask'])
            ->getMock();

        $task = new Task();
        $addTask = $this->taskRepo->expects($this->exactly($addTaskCalls))
            ->method('addTask')
            ->with($summary, $userId);

        $logTaskCount = !$exception ? 1 : 0;

        $logTask = $service->expects($this->exactly($logTaskCount))
            ->method('logTask');

        if ($exception) {
            $this->expectException($exception['type']);
            $addTask->willThrowException(new $exception['type']($exception['message'], $exception['code']));
        } else {
            $addTask->willReturn($task);
            $logTask->with($user, $task);
        }

        $result = $service->addTask($summary, $userId);
        if ($exception) {
            $this->assertEquals($exception['code'], $result->getStatusCode());
            $this->assertInstanceOf($exception['type'], $result);
        }else{
            $this->assertEquals($task, $result);
        }
    }

    public function provideAddTask(): array
    {
        return [
            'user exists, task success' => [
                'userId' => 1,
                'summary' => 'xyz',
                'user' => (object)[
                    'user' => 'info',
                ],
                'addTaskCalls' => 1,
            ],
            'user exists, task unsuccessful' => [
                'userId' => 1,
                'summary' => 'xyz',
                'user' => (object)[
                    'user' => 'info',
                ],
                'addTaskCalls' => 1,
                'exception' => [
                    'type' => \Exception::class,
                    'message' => 'database error',
                    'code' => 500,
                ],
            ],
            'user does not exist' => [
                'userId' => 14,
                'summary' => 'xyz',
                'user' => null,
                'addTaskCalls' => 0,
                'exception' => [
                    'type' => \Exception::class,
                    'message' => 'Invalid User',
                    'code' => 400,
                ],
            ],
        ];
    }
}
