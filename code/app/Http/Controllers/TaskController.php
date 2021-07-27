<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class TaskController extends BaseController
{
    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function show(int $userId): JsonResponse
    {
        try{
            $tasks = $this->service->getTasks($userId);
            return new JsonResponse($tasks, 200);
        } catch(\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        $validateRequest = $this->validateRequest($request);

        if ($validateRequest->fails()) {
            return new JsonResponse($validateRequest->getMessageBag(), 400);
        }

        try {
            $task = $this->service->addTask($request->input('summary'), $userId);
            return new JsonResponse($task, 200);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }

    }

    protected function validateRequest(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), Task::$createRules);
    }
}
