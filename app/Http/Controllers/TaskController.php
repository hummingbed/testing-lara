<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Helpers\ResponseMessages;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollectionResource;



class TaskController extends Controller
{
    use HttpResponses;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function createTask(CreateTaskRequest $request)
    {
        $task = $this->taskService->saveTaskData($request);
        return $this->successHttpMessage(
            $task,
            ResponseMessages::getSuccessMessage('Task created successfully'),
            201
        );
    }


    public function updateTask(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskService->updateTaskData($request, $id);
        return $this->successHttpMessage(
            $task,
            ResponseMessages::getSuccessMessage('Task updated succcessfully'),
            201
        );
    }

    public function getSingleTaskById($id)
    {
        $task = $this->taskService->findTaskById($id);
        $taskResource = new TaskResource($task);
        return $this->successHttpMessage(
            $taskResource,
            ResponseMessages::getSuccessMessage('Task'),
            200
        );
    }


    public function getAllTask(Request $request)
    {
        $tasks = $this->taskService->queryTask($request);
        $taskResource = TaskCollectionResource::collection($tasks);

        return $this->successHttpMessage(
            $taskResource,
            ResponseMessages::getSuccessMessage('Task retrieved successfully'),
            200
        );
    }

    public function deleteTask($id)
    {
        $task = $this->taskService->deleteTaskById($id);
        return $this->successHttpMessage(
            $task,
            ResponseMessages::getSuccessMessage('Task deleted successfully'),
            200
        );
    }

}
