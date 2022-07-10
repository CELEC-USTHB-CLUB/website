<?php

namespace Tests\Feature;

use App\Training;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestGettingAllCourses extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        Training::factory()->count(100)->create()
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }
}
