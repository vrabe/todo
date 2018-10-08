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
     * @return TaskController
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
    public function index(Request $request) : JsonResponse
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
        if ($task != null) {
            return response()->json($task);
        } else {
            return response()->json(['message' => 'Not Found.'], 404);
        }
    }

    /**
    * Create a task.
    *
    * @param Request $request
    * @return Illuminate\Http\JsonResponse
    */
    public function store(Request $request) : JsonResponse
    {
        $input = json_decode($request->getContent(), true);
        $validatedInput = $request->validate([
            'project_id' => 'required|numeric',
            'time_needed' => 'numeric',
            'priority' => 'required|max:32',
            'status' => 'required|max:32',
            'summary' => 'required|max:128',
            'start_time' => 'date',
            'due_time' => 'date',
            'description' => ''
        ]);
        $this->repository->createTask($validatedInput);
        return response()->json(['message' => 'Created.'], 201);
    }

    /**
    * Update a task.
    *
    * @param Request $request
    * @param int $id
    * @return Illuminate\Http\JsonResponse
    */
    public function update(Request $request, int $id) : JsonResponse
    {
        $input = json_decode($request->getContent(), true);
        $validatedInput = $request->validate([
            'project_id' => 'required|numeric',
            'time_needed' => 'nullable|numeric',
            'priority' => 'required|max:32',
            'status' => 'required|max:32',
            'summary' => 'required|max:128',
            'start_time' => 'nullable|date',
            'due_time' => 'nullable|date',
            'description' => 'nullable'
        ]);
        $this->repository->updateTaskById($id, $validatedInput);
        return response()->json(['message' => 'Updated.'], 200);
    }
}
