<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository extends BaseRepository
{
    public function getModel(): Task
    {
        return new Task();
    }
}
