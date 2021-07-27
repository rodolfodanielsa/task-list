<?php

namespace App\Repositories;

use App\Models\Task;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TaskRepositoryTest extends TestCase
{
    protected Task $task;

    public function setUp(): void
    {
        $this->task = new Task();
    }

    /**
     * @dataProvider provideAddTask
     */
    public function testAddTask(string $summary, int $userId, ?int $willReturn, ?array $exception = null)
    {
        $testDate = Carbon::create(2021, 5, 20, 23, 15);
        Carbon::setTestNow($testDate);

        $repo = $this->getMockBuilder(TaskRepository::class)
            ->onlyMethods(['insertTask'])
            ->setConstructorArgs([$this->task])
            ->getMock();

        if ($exception) {
            $this->expectException($exception['type']);
            $repo->expects($this->once())
                ->method('insertTask')
                ->with($summary, $userId, $testDate)
                ->willThrowException(new $exception['type']($exception['message'], $exception['code']));
        } else {
            $repo->expects($this->once())
                ->method('insertTask')
                ->with($summary, $userId, $testDate)
                ->willReturn($willReturn);
        }

        $this->task->setAttribute('summary', $summary);
        $this->task->setAttribute('user_id', $userId);
        $this->task->setAttribute('date', $testDate);

        $result = $repo->addTask($summary, $userId);
        if ($exception) {
            $this->assertEquals($exception['type'], $result->getStatusCode());
        }
        $this->assertEquals($this->task, $result);

    }

    public function provideAddTask(): array
    {
        return [
            'task is added successfully' => [
                'summary' => 'Lorem Ipsum',
                'userId' => 1,
                'willReturn' => 14,
            ],
            'task is not added' => [
                'summary' => 'Lorem Ipsum',
                'userId' => 1,
                'willReturn' => null,
                'exception' => [
                    'type' => \Exception::class,
                    'message' => 'database error',
                    'code' => 500,
                ],
            ],
        ];
    }
}
