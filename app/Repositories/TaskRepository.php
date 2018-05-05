<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskDescription;

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
        $task = Task::create($data);
        if(array_key_exists('description', $data)){
            $description = new TaskDescription();
            $description->text = $data['description'];
            $task->description()->save($description);
            $task->save();
        }
    }
}

?>
