$article<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\TaskDescription;
use App\Models\List;
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
            $listEntry = new Project();
            $listEntry->name = 'Project ' . $i;
            $listEntry->status = $status[$i % 2];
            $listEntry->save();
            $listEntry = null;
        }
        for ($i = 1; $i <= 100; $i ++) {
            $entry = new Task();
            $entry->list_id = floor($i / 10);
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
        $this->assertEquals(floor($i / 10), $article->list_id);
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
     * A  test about deleteTaskById method.
     *
     * @return void
     */
    public function testDeleteTaskById()
    {
        $this->seedData();
        $i = rand(101, 200);
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
        $task1 = ['list_id' => 10,
                  'time_needed' => 1000,
                  'priority' => 'high',
                  'status' => 'new',
                  'summary' => 'a summary',
                  'start_time' => $now,
                  'due_time' => $now,
                  'description' => 'description = ='
                ];
        $this->repository->createTask($task1);
        $returnedTask1 = $this->repository->getTaskById(201);
        $this->assertEquals(10, $returnedTask1->list_id);
        $this->assertCount(1, $returnedTask1->description()->get());
        $this->assertEquals('description = =', $returnedTask1->description()->get()[0]->text);
        $this->assertEquals(1000, $returnedTask1->time_needed);
        $this->assertEquals('high', $returnedTask1->priority);
        $this->assertEquals('new', $returnedTask1->status);
        $this->assertEquals('a summary', $returnedTask1->summary);
        $this->assertEquals($returnedTask1->start_time, $now);
        $this->assertEquals($returnedTask1->due_time, $now);

        //case 2: all fields without description are filled.
        $task2 = ['list_id' => 10,
                  'time_needed' => 1000,
                  'priority' => 'high',
                  'status' => 'new',
                  'summary' => 'a summary',
                  'start_time' => $now,
                  'due_time' => $now
                ];
        $this->repository->createTask($task2);
        $returnedTask2 = $this->repository->getTaskById(202);
        $this->assertEquals(10, $returnedTask2->list_id);
        $this->assertCount(0, $returnedTask2->description()->get());
        $this->assertEquals(1000, $returnedTask2->time_needed);
        $this->assertEquals('high', $returnedTask2->priority);
        $this->assertEquals('new', $returnedTask2->status);
        $this->assertEquals('a summary', $returnedTask2->summary);
        $this->assertEquals($returnedTask2->start_time, $now);
        $this->assertEquals($returnedTask2->due_time, $now);

        //case 3: only necessary fields
        $task3 = ['list_id' => 10,
                  'priority' => 'high',
                  'status' => 'new',
                  'summary' => 'a summary'
                ];
        $this->repository->createTask($task3);
        $returnedTask3 = $this->repository->getTaskById(203);
        $this->assertEquals(10, $returnedTask3->list_id);
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
         $task1 = ['list_id' => 10,
                   'time_needed' => 1000,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary',
                   'start_time' => $now,
                   'due_time' => $now,
                   'description' => 'description = ='
                 ];
         $task2 = ['list_id' => 10,
                   'time_needed' => 1000,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary',
                   'start_time' => $now,
                   'due_time' => $now
                 ];
         $task3 = ['list_id' => 10,
                   'priority' => 'high',
                   'status' => 'new',
                   'summary' => 'a summary'
                 ];
         $this->repository->createTask($task1);
         $this->repository->createTask($task2);
         $this->repository->createTask($task3);
         //case 1: update a task which description is filled.
         $now = date("Y-m-d H:i:s");
         $task1_fixed = ['list_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now,
                        'description' => 'description'
                 ];
         $this->repository->updateTaskById(204, $task1_fixed);
         $returnedTask1 = $this->repository->getTaskById(204);
         $this->assertEquals(5, $returnedTask1->list_id);
         $this->assertCount(1, $returnedTask1->description()->get());
         $this->assertEquals('description', $returnedTask1->description()->get()[0]->text);
         $this->assertEquals(100, $returnedTask1->time_needed);
         $this->assertEquals('medium', $returnedTask1->priority);
         $this->assertEquals('finished', $returnedTask1->status);
         $this->assertEquals('a summary Mk.2', $returnedTask1->summary);
         $this->assertEquals($returnedTask1->start_time, $now);
         $this->assertEquals($returnedTask1->due_time, $now);

         //case 2: update a task which description is not filled.
         $task2_fixed = ['list_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now,
                        'description' => 'description'
                 ];
         $this->repository->updateTaskById(205, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById(205);
         $this->assertEquals(5, $returnedTask2->list_id);
         $this->assertCount(1, $returnedTask2->description()->get());
         $this->assertEquals('description', $returnedTask2->description()->get()[0]->text);
         $this->assertEquals(100, $returnedTask2->time_needed);
         $this->assertEquals('medium', $returnedTask2->priority);
         $this->assertEquals('finished', $returnedTask2->status);
         $this->assertEquals('a summary Mk.2', $returnedTask2->summary);
         $this->assertEquals($returnedTask2->start_time, $now);
         $this->assertEquals($returnedTask2->due_time, $now);

         //case 2-1: update a task to remove its description.
         $task2_fixed = ['list_id' => 5,
                        'time_needed' => 100,
                        'priority' => 'medium',
                        'status' => 'finished',
                        'summary' => 'a summary Mk.2',
                        'start_time' => $now,
                        'due_time' => $now
                 ];
         $this->repository->updateTaskById(205, $task2_fixed);
         $returnedTask2 = $this->repository->getTaskById(205);
         $this->assertEquals(5, $returnedTask2->list_id);
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
