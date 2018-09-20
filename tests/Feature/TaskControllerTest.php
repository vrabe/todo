<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {

    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function availablilityTest()
    {
        $response = $this->call('GET', '/api/tasks');
        $response->assertStatus(200);
    }
}
