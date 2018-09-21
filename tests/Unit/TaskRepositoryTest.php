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
    protected static $isSetUp = false;

    /**
     * add 100 seed tasks
     */
    protected function seedData()
    {
        $priority = ['low', 'medium', 'high'];
        $status = ['new', 'finished'];
        for ($i = 1; $i <= 10; $i ++) {
            $projectEntry = new Project();
            $projectEntry->name = 'Project ' . $i;
            $projectEntry->status = $status[$i % 2];
            $projectEntry->save();
            $projectEntry = null;
        }
        for ($i = 1; $i <= 100; $i ++) {
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
            $entry = null;
            $description = null;
        }
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
     * A  test about getTaskById method.
     *
     * @return void
     */
    public function testGetTaskById()
    {
        $this->seedData();
        $priority = ['low', 'medium', 'high'];
        $status = ['new', 'finished'];
        $i = rand(1, 100);
        $article = $this->repository->getTaskById($i);
        $this->assertEquals(floor($i / 10), $article->project_id);
        $this->assertCount(1, $article->description()->get());
        $this->assertEquals('For task ' . $i, $article->description()->get()[0]->text);
        $this->assertEquals(3600 * $i, $article->time_needed);
        $this->assertEquals($priority[$i % 3], $article->priority);
        $this->assertEquals($status[$i % 2], $article->status);
        $this->assertTrue(strlen($article->summary) == 128);
        $this->assertGreaterThanOrEqual(strtotime($article->start_time), time());
        $this->assertGreaterThanOrEqual(strtotime($article->due_time), time());

        $article2 = $this->repository->getTaskById(0);
        $this->assertNull($article2);
    }

    /**
     * A  test about getAllTasks method.
     *
     * @return void
     */
    public function testGetAllTasks()
    {
        $this->seedData();
        $priority = ['low', 'medium', 'high'];
        $status = ['new', 'finished'];
        $i = rand(1, 100);
        $tasks = $this->repository->getAllTasks();
        $this->assertEquals(($i + 100), $tasks[$i]->id);
        $this->assertEquals(floor($i / 10), $tasks[$i]->project_id);
        $this->assertCount(1, $tasks[$i]->description()->get());
        $this->assertEquals('For task ' . $i, $tasks[$i]->description()->get()[0]->text);
        $this->assertEquals(3600 * $i, $tasks[$i]->time_needed);
        $this->assertEquals($priority[$i % 3], $tasks[$i]->priority);
        $this->assertEquals($status[$i % 2], $tasks[$i]->status);
        $this->assertTrue(strlen($tasks[$i]->summary) == 128);
        $this->assertGreaterThanOrEqual(strtotime($tasks[$i]->start_time), time());
        $this->assertGreaterThanOrEqual(strtotime($tasks[$i]->due_time), time());
    }

    /**
     * A  test about deleteTaskById method.
     *
     * @return void
     */
    public function testDeleteTaskById()
    {
        $this->seedData();
        $i = rand(201, 300);
        $this->repository->deleteTaskById($i);
        $this->assertNull(Task::where('id', $i)->first());
    }

    /**
     * A  test about createTask method.
     *
     * @return void
     */
    public function testCreateTask()
    {
        //case 1: all fields are filled.
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
        $this->repository->createTask($task1);
        $returnedTask1 = $this->repository->getTaskById(301);
        $this->assertEquals(10, $returnedTask1->project_id);
        $this->assertCount(1, $returnedTask1->description()->get());
        $this->assertEquals('description = =', $returnedTask1->description()->get()[0]->text);
        $this->assertEquals(1000, $returnedTask1->time_needed);
        $this->assertEquals('high', $returnedTask1->priority);
        $this->assertEquals('new', $returnedTask1->status);
        $this->assertEquals('a summary', $returnedTask1->summary);
        $this->assertEquals($returnedTask1->start_time, $now);
        $this->assertEquals($returnedTask1->due_time, $now);

        //case 2: all fields without description are filled.
        $task2 = ['project_id' => 10,
                  'time_needed' => 1000,
                  'priority' => 'high',
                  'status' => 'new',
                  'summary' => 'a summary',
                  'start_time' => $now,
                  'due_time' => $now
                ];
        $this->repository->createTask($task2);
        $returnedTask2 = $this->repository->getTaskById(302);
        $this->assertEquals(10, $returnedTask2->project_id);
        $this->assertCount(0, $returnedTask2->description()->get());
        $this->assertEquals(1000, $returnedTask2->time_needed);
        $this->assertEquals('high', $returnedTask2->priority);
        $this->assertEquals('new', $returnedTask2->status);
        $this->assertEquals('a summary', $returnedTask2->summary);
        $this->assertEquals($returnedTask2->start_time, $now);
        $this->assertEquals($returnedTask2->due_time, $now);

        //case 3: only necessary fields
        $task3 = ['project_id' => 10,
                  'priority' => 'high',
                  'status' => 'new',
                  'summary' => 'a summary'
                ];
        $this->repository->createTask($task3);
        $returnedTask3 = $this->repository->getTaskById(303);
        $this->assertEquals(10, $returnedTask3->project_id);
        $this->assertCount(0, $returnedTask3->description()->get());
        $this->assertNull($returnedTask3->time_needed);
        $this->assertEquals('high', $returnedTask3->priority);
        $this->assertEquals('new', $returnedTask3->status);
        $this->assertEquals('a summary', $returnedTask3->summary);
        $this->assertNull($returnedTask3->start_time);
        $this->assertNull($returnedTask3->due_time);
    }

    /**
     * A  test about updateTaskById method.
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
         $this->repository->createTask($task1);
         $this->repository->createTask($task2);
         $this->repository->createTask($task3);
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
         $this->repository->updateTaskById(304, $task1_fixed);
         $returnedTask1 = $this->repository->getTaskById(304);
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
         $this->repository->updateTaskById(305, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById(305);
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
         $this->repository->updateTaskById(305, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById(305);
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
