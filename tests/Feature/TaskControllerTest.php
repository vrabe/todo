<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {

    }

    /**
     * test GET /api/tasks route and TaskController@index method.
     *
     * @return void
     */
    public function testGetTaskRoute()
    {
        $response = $this->call('GET', '/api/tasks');
        $response->assertStatus(200);
    }
}
