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

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function availablilityTest()
    {
        $this->call('GET', '/tasks');
        $this->assertResponseOk();
    }
}
