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
        }else{
            Task::create($data);
        }
    }

    /**
     * Update a task by its id.
     *
     * @param $id task id
     * @return mixed the task
     */
    public function updateTaskById($id, $data)
    {
        if(array_key_exists('description', $data)){
            $descriptionText = $data['description'];
            unset($data['description']);
            $task = Task::find($id);
            $task->update($data);
            if($task->description === null){
                $description = new TaskDescription();
                $description->text = $descriptionText;
                $task->description()->save($description);
            }else{
                $task->description()->update(['text' => $description]);
            }
        }else{
            Task::find($id)->update($data);
        }
    }
}

?>
