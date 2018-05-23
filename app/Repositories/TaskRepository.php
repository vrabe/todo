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
        if(array_key_exists('description', $data)){
            $descriptionText = $data['description'];
            unset($data['description']);
            $task = Task::create($data);
            $description = new TaskDescription();
            $description->text = $descriptionText;
            $task->description()->save($description);
            $task->save();
        }else{
            $task = Task::create($data);
        }
    }

    /**
     * Update a task by its id.
     *
     * @param $id task id
     * @return mixed the task
     */
    public function updateTaskById($id)
    {
        if(array_key_exists('description', $data)){
            $descriptionText = $data['description'];
            unset($data['description']);
            $task = Task::where('id', $id)->update($data);
            $description = new TaskDescription();
            $description->text = $descriptionText;
            $task->description()->save($description);
            $task->save();
        }else{
            $task = Task::where('id', $id)->update($data);
        }
    }
}

?>
