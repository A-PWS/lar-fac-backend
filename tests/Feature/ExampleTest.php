<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_api_running_route(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200)->assertJson(['API running'
        ]);
    }
}
