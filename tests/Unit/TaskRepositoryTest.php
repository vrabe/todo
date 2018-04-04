<?php

use App\Repositories\TaskRepository;

class TaskRepository extends TestCase
{
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
        for ($i = 0; $i < 100; $i ++) {
            Task::create([
                'project_id' => floor($i / 10) ,
                'description_id' => $i ,
                'time_needed' => 60 * 60 * $i ,
                'priority' => $priority[$i % 3] ,
                'status' => $status[$i % 2] ,
                'summary' => str_random(128) ,
                'start_time' => date("Y-m-d H:i:s") ,
                'due_time' => date("Y-m-d H:i:s")
            ]);
        }
    }

    public function setUp()
    {
        parent::setUp();

        $this->initDatabase();
        $this->seedData();

        $this->repository = new ArticleRepository();
    }

    public function tearDown()
    {
        $this->resetDatabase();
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
        $i = rand(0, 99);
        $articles = $this->repository->getTaskById($i);
        $this->assertEquals(1, count($articles));
        $this->assertEquals(floor($i) / 10, $articles[0]->project_id);
        $this->assertEquals($i, $articles[0]->description_id);
        $this->assertEquals(3600 * $i, $articles[0]->time_needed);
        $this->assertEquals($priority[$i % 3], $articles[0]->priority);
        $this->assertEquals($status[$i % 2], $articles[0]->status);
        $this->assertTrue(strlen($articles[0]->summary) == 128);
        $this->assertTrue(strtotime($articles[0]->start_time));
        $this->assertTrue(strtotime($articles[0]->due_time));
    }
}

?>
