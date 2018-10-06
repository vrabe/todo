<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;

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
}
