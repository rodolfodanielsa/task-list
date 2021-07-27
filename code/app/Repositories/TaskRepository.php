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
        return DB::select("SELECT t.id, t.date, u.id as user_id, u.name, r.role_name, t.summary
        FROM tasks t
        INNER JOIN users u ON t.user_id = u.id
        INNER JOIN roles r ON r.id = u.role_id
        ORDER BY t.id ASC");
    }

    public function getTasksByUser(int $userId): array
    {
        return DB::select("SELECT t.id, t.date, u.id as user_id, u.name, r.role_name, t.summary
        FROM tasks t
        INNER JOIN users u ON t.user_id = u.id
        INNER JOIN roles r ON r.id = u.role_id
        WHERE u.id = ?", [$userId]);
    }

    public function addTask(string $summary, int $userId): Task
    {
        $date = Carbon::now()->format("Y-m-d H:i:s");

        $this->task->setAttribute('summary', $summary);
        $this->task->setAttribute('user_id', $userId);
        $this->task->setAttribute('date', $date);

        try {
            $id = $this->insertTask($summary, $userId, $date);
            $this->task->setAttribute('id', $id);
            return $this->task;
        } catch (\Exception $e) {
            throw new \Exception("Database Error", 500);
        }
    }

    protected function insertTask(string $summary, int $userId, string $date): int
    {
        $insert = DB::insert("INSERT INTO tasks (summary, user_id, date) VALUES (?, ?, ?)", [$summary, $userId, $date]);
        return DB::getPdo()->lastInsertId();
    }
}
