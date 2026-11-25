<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_health_responds_and_api_requires_authentication(): void
    {
        $this->get('/up')->assertStatus(200);
        $this->getJson('/me')->assertStatus(401)->assertJsonPath('error.code', 'UNAUTHENTICATED');
    }
}
