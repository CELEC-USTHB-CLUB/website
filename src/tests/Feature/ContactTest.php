<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseTransactions;

    public function testSavingContact()
    {
        $response = $this->post('/api/contact/create', [
            'email' => 'test@mail.com',
            'message' => 'Hello world',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('contacts', 1);
    }
}
