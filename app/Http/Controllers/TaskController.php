<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json($this->repository->getPaginated(10));
    }

     /**
     * Return the task.
     *
     * @param  int $taskId
     * @return Illuminate\Http\JsonResponse
     */
     public function show(int $id) : JsonResponse
     {
         $task = $this->repository->getTaskById($id);
         if($task != null)
             return response()->json($task);
         else
             return response()->json(['message' => 'Not Found.'], 404);
     }

}
