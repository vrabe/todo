<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    /**
     * The task repository mock.
     *
     * @var TaskRepository
     */
    protected $repositoryMock = null;

    public function setUp()
    {
        parent::setUp();
        $this->repositoryMock = Mockery::mock('App\Repositories\TaskRepository');
        $this->app->instance('App\Repositories\TaskRepository', $this->repositoryMock);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * test GET /api/tasks route and TaskController@index method.
     *
     * @return void
     */
    public function testGetTaskRoute()
    {
        $this->repositoryMock
            ->shouldReceive('getPaginated')
            ->with(10)
            ->once();

        $response = $this->call('GET', '/api/tasks');
        $response->assertStatus(200);
    }

    /**
     * test GET /api/tasks/{id} route and TaskController@show method.
     *
     * @return void
     */
    public function testGetTaskByIdRoute()
    {
        $this->repositoryMock
            ->shouldReceive('getTaskById')
            ->with(1)
            ->once()
            ->andReturn(new Task());

        $response = $this->call('GET', '/api/tasks/1');
        $response->assertStatus(200);

        $this->repositoryMock
            ->shouldReceive('getTaskById')
            ->with(0)
            ->once()
            ->andReturn(null);

        $response = $this->call('GET', '/api/tasks/0');
        $response->assertStatus(404);
    }
}
