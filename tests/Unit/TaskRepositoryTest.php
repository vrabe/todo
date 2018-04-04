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
        for ($i = 1; $i <= 100; $i ++) {
            Task::create([
                'project_id' => floor($i) ,
                'description_id' => $i ,
                'time_needed' => 60 * 60 * $i ,
                'priority' => $priority[$i % 3] ,
                'status' => $status[$i % 2] ,
                'summary' => str_random(128) ,
                'start_time' => date() ,
                'due_time' => date()
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
}

?>
