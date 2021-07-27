<?php

namespace App\Repositories;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getAllTasks(): array
    {
        return DB::select("SELECT * FROM tasks");
    }

    public function getTasksByUser(int $userId): array
    {
        return DB::select("SELECT t.id, t.summary, t.created_at
        FROM tasks t
        INNER JOIN users u ON t.user_id = u.id
        WHERE u.id = ?", [$userId]);
    }

    public function addTask(string $summary, int $userId): Task
    {
        $date = Carbon::now()->format("Y-m-d H:i:s");

        $this->task->setAttribute('summary', $summary);
        $this->task->setAttribute('user_id', $userId);
        $this->task->setAttribute('date', $date);

        try {
            $this->insertTask($summary, $userId, $date);
            return $this->task;
        } catch (\Exception $e) {
            throw new \Exception("Database Error", 500);
        }
    }

    protected function insertTask(string $summary, int $userId, string $date): bool
    {
        return DB::insert("INSERT INTO tasks (summary, user_id, date) VALUES (?, ?, ?)", [$summary, $userId, $date]);
    }
}
