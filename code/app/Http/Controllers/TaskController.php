<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TaskController extends BaseController
{
    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request, int $userId): JsonResponse
    {
        return new JsonResponse($this->service->getTasks($userId), 200);
    }

    public function store(Request $request, int $userId): JsonResponse
    {
        return new JsonResponse($this->service->addTask($request, $userId), 200);
    }
}
