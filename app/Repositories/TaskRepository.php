<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    /**
     * Get a task by its id.
     *
     * @param $id task id
     * @return App\Models\Task the task
     */
    public function getTaskById($id)
    {
        return Task::where('id', $id)->first();
    }
}

?>
