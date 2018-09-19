<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\TaskRepository;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    /**
     * @var TaskController
     */
    protected $controller = null;

    public function setUp()
    {
        parent::setUp();
        $this->controller = new TaskController(new TaskRepository(new Task()));
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}
