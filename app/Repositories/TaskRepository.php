<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskDescription;

class TaskRepository
{
    // The task model
    protected $model;

    /**
    * Create a new task repository instance.
    *
    * @param Model $task
    * @return TaskRepository
    */
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * Get a task by its id.
     *
     * @param $id task id
     * @return mixed the task
     */
    public function getTaskById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get all tasks.
     *
     * @return Collection the tasks
     */
    public function getAllTasks()
    {
        return $this->model->orderBy('id')->get();
    }

    /**
     * Get paginated tasks.
     *
     * @return Illuminate\Pagination\Paginator the paginated tasks
     */
     public function getPaginated(int $perPage = null)
     {
         return $this->model->orderBy('id')->paginate($perPage);
     }

    /**
     * Create a task.
     *
     * @param array $data
     * @return Task created task
     */
    public function createTask(array $data) {
        if(array_key_exists('description', $data)){
            $descriptionText = $data['description'];
            unset($data['description']);
            $task = $this->model->create($data);
            $description = new TaskDescription();
            $description->text = $descriptionText;
            $task->description()->save($description);
        }else{
            $task = $this->model->create($data);
        }
        return $task;
    }

    /**
     * Update a task by its id.
     *
     * @param $id task id
     */
    public function updateTaskById($id, $data)
    {
        if(array_key_exists('description', $data)){
            $descriptionText = $data['description'];
            unset($data['description']);
            $task = $this->model->find($id);
            $task->update($data);
            if($task->description === null){
                $description = new TaskDescription();
                $description->text = $descriptionText;
                $task->description()->save($description);
            }else{
                $task->description()->update(['text' => $descriptionText]);
            }
        }else{
            $task = $this->model->find($id);
            $task->update($data);
            if($task->description !== null){
                $task->description()->delete();
            }
        }
    }

    /**
     * Delete a task by its id.
     *
     * @param $id task id
     */
    public function deleteTaskById($id)
    {
        $this->model->destroy($id);
    }
}

?>
