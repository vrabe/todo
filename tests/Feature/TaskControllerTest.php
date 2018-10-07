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
     * test GET /api/v1/tasks route and TaskController@index method.
     *
     * @return void
     */
    public function testGetTaskRoute()
    {
        $this->repositoryMock
            ->shouldReceive('getPaginated')
            ->with(10)
            ->once();

        $response = $this->call('GET', '/api/v1/tasks');
        $response->assertStatus(200);
    }

    /**
     * test GET /api/v1/tasks/{id} route and TaskController@show method.
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

        $response = $this->call('GET', '/api/v1/tasks/1');
        $response->assertStatus(200);

        $this->repositoryMock
            ->shouldReceive('getTaskById')
            ->with(0)
            ->once()
            ->andReturn(null);

        $response = $this->call('GET', '/api/v1/tasks/0');
        $response->assertStatus(404);
    }

    /**
     * test POST /api/v1/tasks/ route and TaskController@store method.
     *
     * @return void
     */
    public function testPostTaskRoute()
    {
        $data = ['project_id' => 1,
                'time_needed' => 100,
                'priority' => 'high',
                'status' => 'new',
                'summary' => 'summary',
                'start_time' => date("Y-m-d H:i:s"),
                'due_time' => date("Y-m-d H:i:s"),
                'description' => 'description'];
        $this->repositoryMock
            ->shouldReceive('createTask')
            ->with($data)
            ->once()
            ->andReturn(new Task());
        $response = $this->json('POST', '/api/v1/tasks', $data);
        $response->assertStatus(201);
        $response = $this->json('POST', '/api/v1/tasks', []);
        $response->assertStatus(422);
    }

    /**
     * test PUT /api/v1/tasks/{id} route and TaskController@update method.
     *
     * @return void
     */
    public function testPutTaskByIdRoute()
    {
        $data = ['project_id' => 1,
                'time_needed' => 100,
                'priority' => 'high',
                'status' => 'new',
                'summary' => 'summary',
                'start_time' => date("Y-m-d H:i:s"),
                'due_time' => date("Y-m-d H:i:s"),
                'description' => 'description'];
        $this->repositoryMock
            ->shouldReceive('updateTaskById')
            ->with(1, $data)
            ->once();
        $response = $this->json('POST', '/api/v1/tasks/1', $data);
        $response->assertStatus(200);
        $response = $this->json('POST', '/api/v1/tasks/0', $data);
        $response->assertStatus(404);
        $response = $this->json('POST', '/api/v1/tasks/1', []);
        $response->assertStatus(422);
    }
}
