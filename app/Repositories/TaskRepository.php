<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    /**
     * Get a task by its id.
     *
     * @param $id task id
     * @return mixed the task
     */
    public function getTaskById($id)
    {
        return Task::where('id', $id)->first();
    }

    /**
     * Create a task.
     *
     * @param array $data
     * @return mixed
     */
    public function createTask(array $data) {
        
    }
}

?>
