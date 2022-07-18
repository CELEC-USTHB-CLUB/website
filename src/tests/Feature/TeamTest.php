<?php

namespace Tests\Feature;

use App\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    public function testGettingTeam()
    {
        Team::factory()->hasImage(1)->count(10)->create();
        $response = $this->get('/api/team/all');
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }
}
