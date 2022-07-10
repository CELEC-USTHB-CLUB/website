<?php

namespace Tests\Feature;

use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GettingTagsTest extends TestCase
{
    use DatabaseTransactions;

    public function testGettingAllTags()
    {
        Tag::factory()->count(50)->create();
        $response = $this->get('/api/tags/get');

        $response->assertStatus(200);
        $response->assertJsonCount(50, "data");
    }
}
