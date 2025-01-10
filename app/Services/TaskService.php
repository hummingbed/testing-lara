<?php

namespace App\Services;

use App\Exceptions\UnprocessableEntityException;
use App\Exceptions\NotFoundException;
use App\Helpers\ResponseMessages;
use App\Repositories\TaskRepository;
use App\Models\Task;


class TaskService extends BaseService
{
    public function __construct(TaskRepository $repository)
    {
        $this->repo = $repository;
    }

   

    public function saveTaskData($request)
    {
        return $this->repo->insert([
            "title"=> $request->title,
            "description"=> $request->description,
            "due_date"=> $request->due_date,
            "priority"=> $request->priority,
        ], );
    }

    public function findTaskById($id)
    {
        $task = $this->repo->findFirst( ['id' => $id ] );
        throw_unless($task, new NotFoundException(
            ResponseMessages::getEntityNotExistMessage("Task")
        ));

        return $task;
    }

    public function updateTaskData($request, $id): mixed
    {
        $updateData = $request->only(['title', 'description', 'due_date', 'priority']);

        // Remove any keys with null values (optional)
        $updateData = array_filter($updateData, function ($value) {
            return !is_null($value);
        });

        $this->findTaskById( $id)->update($updateData);
        return $this->findTaskById( $id );
    }

    public function queryTask($request)
    {
        $searchValue = $request->input('search_value');

        $query = Task::query();
    
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('priority', 'like', '%' . $searchValue . '%')
                  ->orWhere('title', 'like', '%' . $searchValue . '%')
                  ->orWhere('description', 'like', '%' . $searchValue . '%');
            });
        }
    
        $query->orderBy('created_at', 'desc');
        
        $tasks = $query->get();
        return $tasks;
    }

    public function deleteTaskById($id)
    {
        $task = $this->findTaskById( $id );
        return $task->delete();
    }
}