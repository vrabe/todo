<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\TaskDescription;
use App\Models\Project;
use App\Repositories\TaskRepository;

class TaskRepositoryTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var TaskRepository
     */
    protected $repository = null;

    /**
     * add seed tasks
     *
     * @param  int  $qty number of tasks
     * @return array tasks and projects
     */
    protected function seedData(int $qty)
    {
        $priority = ['low', 'medium', 'high'];
        $status = ['new', 'finished'];
        $returnValue = array();
        for ($i = 1; $i <= (($qty < 10) ? $qty : 10); $i ++) {
            $projectEntry = new Project();
            $projectEntry->name = 'Project ' . $i;
            $projectEntry->status = $status[$i % 2];
            $projectEntry->save();
            $returnValue["project"][] = $projectEntry;
            $projectEntry = null;
        }
        for ($i = 1; $i <= $qty; $i ++) {
            $entry = new Task();
            $entry->project_id = floor($i / 10);
            $entry->time_needed = 60 * 60 * $i;
            $entry->priority = $priority[$i % 3];
            $entry->status = $status[$i % 2];
            $entry->summary = str_random(128);
            $entry->start_time = date("Y-m-d H:i:s");
            $entry->due_time = date("Y-m-d H:i:s");
            $entry->save();
            $description = new TaskDescription();
            $description->text = 'For task ' . $i;
            $entry->description()->save($description);
            $entry->save();
            $returnValue["task"][] = $entry;
            $entry = null;
            $description = null;
        }
        return $returnValue;
    }

    /**
     * check 2 tasks are the same.
     *
     * @param  Task  $task1
     * @param  Task  $task2
     */
    protected function assertTaskEquals(Task $task1, Task $task2)
    {
        $this->assertEquals($task1->project_id, $task2->project_id);
        $this->assertEquals(count($task1->description()->get()), count($task2->description()->get()));
        if(count($task1->description()->get()) > 0)
            $this->assertEquals($task1->description()->get()[0]->text, $task2->description()->get()[0]->text);
        $this->assertEquals($task1->time_needed, $task2->time_needed);
        $this->assertEquals($task1->priority, $task2->priority);
        $this->assertEquals($task1->status, $task2->status);
        $this->assertEquals($task1->summary, $task2->summary);
        $this->assertEquals($task1->start_time, $task2->start_time);
        $this->assertEquals($task1->due_time, $task2->due_time);
    }

    public function setUp()
    {
        parent::setUp();
        $this->repository = new TaskRepository(new Task());
    }

    public function tearDown()
    {
        $this->repository = null;
    }

    /**
     * A test about getTaskById method.
     *
     * @return void
     */
    public function testGetTaskById()
    {
        $data = $this->seedData(1);
        $task = $data["task"][0];
        $fetchedTask = $this->repository->getTaskById($data["task"][0]->id);
        $this->assertTaskEquals($fetchedTask, $task);

        $nullTask = $this->repository->getTaskById(0);
        $this->assertNull($nullTask);
    }

    /**
     * A  test about getAllTasks method.
     *
     * @return void
     */
    public function testGetAllTasks()
    {
        $data = $this->seedData(100);
        $tasks = $data["task"];
        $fetchedTasks = $this->repository->getAllTasks();
        for($i = 0 ; $i < 100 ; $i++){
            $task = $tasks[$i];
            $fetchedTask = $fetchedTasks[$i];
            $this->assertTaskEquals($fetchedTask, $task);
        }
    }

    /**
     * A  test about getPaginated method.
     *
     * @return void
     */
    public function testGetPaginated()
    {
        $data = $this->seedData(100);
        $tasks = $data["task"];
        $fetchedTasks = $this->repository->getAllTasks();
        $fetchedArray = json_decode($this->repository->getPaginated(10)->toJson());
        $this->assertEquals($fetchedArray->total, 100);
        $this->assertEquals($fetchedArray->per_page, 10);
        $this->assertEquals($fetchedArray->current_page, 1);
        $this->assertEquals($fetchedArray->last_page, 10);
        $this->assertEquals($fetchedArray->from, 1);
        $this->assertEquals($fetchedArray->to, 10);
        $this->assertArrayHasKey("first_page_url", $fetchedArray);
        $this->assertArrayHasKey("last_page_url", $fetchedArray);
        $this->assertArrayHasKey("next_page_url", $fetchedArray);
        $this->assertArrayHasKey("prev_page_url", $fetchedArray);
        $this->assertArrayHasKey("path", $fetchedArray);
        $this->assertEquals($fetchedArray->total, 100);
        for($i = 0 ; $i < 10 ; $i++){
            $task = $fetchedArray->data[$i];
            $fetchedTask = $fetchedTasks[$i];
            $this->assertEquals($task->id, $fetchedTask->id);
            $this->assertEquals($task->project_id, $fetchedTask->project_id);
            //$this->assertEquals($task->description, $fetchedTask->description()->get()[0]->text); //haven not implement
            $this->assertEquals($task->time_needed, $fetchedTask->time_needed);
            $this->assertEquals($task->priority, $fetchedTask->priority);
            $this->assertEquals($task->status, $fetchedTask->status);
            $this->assertEquals($task->summary, $fetchedTask->summary);
            $this->assertEquals($task->start_time, $fetchedTask->start_time);
            $this->assertEquals($task->due_time, $fetchedTask->due_time);
        }
    }

    /**
     * A test about deleteTaskById method.
     *
     * @return void
     */
    public function testDeleteTaskById()
    {
        $data = $this->SeedData(2);
        $task = $data["task"][0]; //if using the last task, it will cause database dead lock.
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id
        ]);
        $this->repository->deleteTaskById($task->id);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }

    /**
     * A test about createTask method.
     *
     * @return void
     */
    public function testCreateTask()
    {
        //case 1: all fields are filled.
        //case 2: all fields without description are filled.
        //case 3: only necessary fields
        $now = date("Y-m-d H:i:s");
        $tasks = array(   ['project_id' => 10,
                          'time_needed' => 1000,
                          'priority' => 'high',
                          'status' => 'new',
                          'summary' => 'a summary',
                          'start_time' => $now,
                          'due_time' => $now,
                          'description' => 'description = ='
                        ],
                         ['project_id' => 10,
                          'time_needed' => 1000,
                          'priority' => 'high',
                          'status' => 'new',
                          'summary' => 'a summary',
                          'start_time' => $now,
                          'due_time' => $now
                        ],
                         ['project_id' => 10,
                          'priority' => 'high',
                          'status' => 'new',
                          'summary' => 'a summary'
                        ]);
        for($i = 0 ; $i < 3 ; $i++){
            $task = $this->repository->createTask($tasks[$i]);
            $fetchedTask = Task::find($task->id);
            $this->assertTaskEquals($fetchedTask, $task);
        }
    }

    /**
     * A test about updateTaskById method.
     *
     * @depends testCreateTask
     * @return void
     */
     public function testUpdateTaskById()
     {
         $now = date("Y-m-d H:i:s");
         $task1 = ['project_id' => 10,
                   'time_needed' => 1000,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary',
                   'start_time' => $now,
                   'due_time' => $now,
                   'description' => 'description = ='
                 ];
         $task2 = ['project_id' => 10,
                   'time_needed' => 1000,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary',
                   'start_time' => $now,
                   'due_time' => $now
                 ];
         $task3 = ['project_id' => 10,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary'
                 ];
         $task1ID = $this->repository->createTask($task1)->id;
         $task2ID = $this->repository->createTask($task2)->id;
         $task3ID = $this->repository->createTask($task3)->id;
         //case 1: update a task which description is filled.
         $now = date("Y-m-d H:i:s");
         $task1_fixed = ['project_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now,
                        'description' => 'description'
                 ];
         $this->repository->updateTaskById($task1ID, $task1_fixed);
         $returnedTask1 = $this->repository->getTaskById($task1ID);
         $this->assertEquals(5, $returnedTask1->project_id);
         $this->assertCount(1, $returnedTask1->description()->get());
         $this->assertEquals('description', $returnedTask1->description()->get()[0]->text);
         $this->assertEquals(100, $returnedTask1->time_needed);
         $this->assertEquals('medium', $returnedTask1->priority);
         $this->assertEquals('finished', $returnedTask1->status);
         $this->assertEquals('a summary Mk.2', $returnedTask1->summary);
         $this->assertEquals($returnedTask1->start_time, $now);
         $this->assertEquals($returnedTask1->due_time, $now);

         //case 2: update a task which description is not filled.
         $task2_fixed = ['project_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now,
                        'description' => 'description'
                 ];
         $this->repository->updateTaskById($task2ID, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById($task2ID);
         $this->assertEquals(5, $returnedTask2->project_id);
         $this->assertCount(1, $returnedTask2->description()->get());
         $this->assertEquals('description', $returnedTask2->description()->get()[0]->text);
         $this->assertEquals(100, $returnedTask2->time_needed);
         $this->assertEquals('medium', $returnedTask2->priority);
         $this->assertEquals('finished', $returnedTask2->status);
         $this->assertEquals('a summary Mk.2', $returnedTask2->summary);
         $this->assertEquals($returnedTask2->start_time, $now);
         $this->assertEquals($returnedTask2->due_time, $now);

         //case 2-1: update a task to remove its description.
         $task2_fixed = ['project_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now
                 ];
         $this->repository->updateTaskById($task2ID, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById($task2ID);
         $this->assertEquals(5, $returnedTask2->project_id);
         $this->assertCount(0, $returnedTask2->description()->get());
         $this->assertEquals(100, $returnedTask2->time_needed);
         $this->assertEquals('medium', $returnedTask2->priority);
         $this->assertEquals('finished', $returnedTask2->status);
         $this->assertEquals('a summary Mk.2', $returnedTask2->summary);
         $this->assertEquals($returnedTask2->start_time, $now);
         $this->assertEquals($returnedTask2->due_time, $now);
     }
}

?>
