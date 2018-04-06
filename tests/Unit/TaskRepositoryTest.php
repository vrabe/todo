$article<?php

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
            $description->description = 'For task ' . $i;
            $entry->description()->save($description);
            $entry->save();
            $entry = null;
            $description = null;
        }
    }

    public function setUp()
    {
        parent::setUp();

        $this->seedData();

        $this->repository = new TaskRepository();
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
        $priority = ['low', 'medium', 'high'];
        $status = ['new', 'finished'];
        $i = rand(1, 100);
        $article = $this->repository->getTaskById($i);
        $this->assertEquals(floor($i / 10), $article->project_id);
        $this->assertCount(1, $article->description()->get());
        $this->assertEquals('For task ' . $i, $article->description()->get()[0]->description);
        $this->assertEquals(3600 * $i, $article->time_needed);
        $this->assertEquals($priority[$i % 3], $article->priority);
        $this->assertEquals($status[$i % 2], $article->status);
        $this->assertTrue(strlen($article->summary) == 128);
        $this->assertGreaterThanOrEqual(strtotime($article->start_time), time());
        $this->assertGreaterThanOrEqual(strtotime($articles[0]->due_time), time());
    }
}

?>
