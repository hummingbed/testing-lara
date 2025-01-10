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

/**
 * @group Task Management
 * 
 * APIs for managing tasks
 */

class TaskController extends Controller
{
    use HttpResponses;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Create Task
     * 
     * Creates a new task with the provided details.
     * 
     * @header Authorization string required The bearer token used to authenticate the request. Example: Bearer your_token_here
     *
     * 
     * @bodyParam title string required The title of the task. Example: Buy groceries
     * @bodyParam description string The description of the task. Example: Purchase milk, bread, and eggs.
     * @bodyParam priority string The priority of the task (low, medium, high). Example: high
     * @response 201 {
     *   "message": "Task created successfully",
     *   "status": 201
     * }
     */

    // Create a new task
    public function createTask(CreateTaskRequest $request)
    {
        $task = $this->taskService->saveTaskData($request);
        return $this->successHttpMessage(
            $task,
            ResponseMessages::getSuccessMessage('Task created successfully'),
            201
        );
    }


    /**
     * 
     * Update Task Api
     * 
     * Updates an existing task by ID.
     * 
     * @header Authorization string required The bearer token used to authenticate the request. Example: Bearer your_token_here
     *
     * @urlParam id integer required The ID of the task. Example: 1
     * @bodyParam title string The new title of the task. Example: Buy groceries
     * @bodyParam description string The new description of the task. Example: Update shopping list.
     * @bodyParam priority string The new priority of the task (low, medium, high). Example: medium
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Task updated successfully",
     *   "data": {
     *       "id": 1,
     *       "title": "Buy groceries",
     *       "description": "Update shopping list",
     *       "priority": "medium",
     *       "due_date": "2025-01-15"
     *   }
     * }
     */

    public function updateTask(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskService->updateTaskData($request, $id);
        return $this->successHttpMessage(
            $task,
            ResponseMessages::getSuccessMessage('Task updated succcessfully'),
            201
        );
    }

    /**
     * Get Single Task
     * 
     * Fetches a single task by its ID.
     * 
     * @urlParam id integer required The ID of the task. Example: 1
     * 
     * @header Authorization string required The bearer token used to authenticate the request. Example: Bearer your_token_here
     * 
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Buy groceries",
     *     "description": "Purchase milk, bread, and eggs.",
     *     "priority": "high",
     *     "due_date": "2025-01-15"
     *   },
     *   "message": "Task retrieved successfully",
     *   "status": 200
     * }
     */

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

    /**
     * Get All Tasks
     * 
     * Fetches all tasks with optional filtering and sorting.
     * 
     * @header Authorization string required The bearer token used to authenticate the request. Example: Bearer your_token_here
     *
     * 
     * @queryParam search_value string Optional search term to filter tasks. Example: high
     * @queryParam sort_by string Optional column to sort by. Example: due_date
     * @queryParam sort_order string Optional sort order (asc or desc). Example: asc
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Buy groceries",
     *       "priority": "high",
     *       "due_date": "2025-01-15"
     *     },
     *     {
     *       "id": 2,
     *       "title": "Complete assignment",
     *       "priority": "medium",
     *       "due_date": "2025-01-20"
     *     }
     *   ],
     *   "message": "Tasks retrieved successfully",
     *   "status": 200
     * }
     */

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

    /**
     * Delete Task
     * @header Authorization string required The bearer token used to authenticate the request. Example: Bearer your_token_here
     *
     * Deletes a task by its ID.
     * 
     * @urlParam id integer required The ID of the task. Example: 1
     * @response 200 {
     *   "message": "Task deleted successfully",
     *   "status": 200
     * }
     */

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
