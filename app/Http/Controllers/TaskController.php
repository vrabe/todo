<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->repository = $tasks;
    }

    /**
     * Show list of tasks.
     *
     * @param Request $request
     *
     * @return
     */
    public function index(Request $request)
    {
        return $this->repository->getPaginated(10);
    }

     /**
     * Return the task.
     *
     * @param  int $taskId
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->repository->getTaskById($id);
    }

}
