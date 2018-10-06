<?php

namespace Tests\Feature;

use Tests\TestCase;

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
        $this->repositoryMock = Mockery::mock(App\Repositories\TaskRepository::class);
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
        $exampleJson = '{"current_page":1,"data":[{"id":110,"created_at":"2018-10-06 06:22:29","updated_at":"2018-10-06 06:22:29","project_id":1,"time_needed":360,"priority":"high","status":"new","summary":"summary","start_time":"2018-10-06 06:22:29","due_time":"2018-10-07 00:00:00"}],"first_page_url":"https:\/\/example.com\/api\/tasks?page=1","from":1,"last_page":1,"last_page_url":"https:\/\/example.com\/api\/tasks?page=1","next_page_url":null,"path":"https:\/\/example.com\/api\/tasks","per_page":10,"prev_page_url":null,"to":1,"total":1}';
        $PaginatorMock = Mockery::mock(Illuminate\Pagination\Paginator::class);
        $PaginatorMock
            ->shouldReceive('toJson')
            ->once()
            ->andReturn($exampleJson);
        $this->repositoryMock
            ->shouldReceive('getPaginated')
            ->with(10)
            ->once()
            ->andReturn($PaginatorMock);

        $response = $this->call('GET', '/api/tasks');
        $response->assertStatus(200);
        $response->assertExactJson(json_decode($exampleJson, true));
    }
}
