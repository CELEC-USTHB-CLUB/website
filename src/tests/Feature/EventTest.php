<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    public function testGet()
    {
        $events = Event::factory()->hasImage(1)->count(10)->create();

        $response = $this->get('/api/events/all');

        $response->assertStatus(200);
        $response->assertJsonCount($events->count(), 'data');
    }

    public function testGettingSignleEvent()
    {
        $events = Event::factory()->hasImage(1)->count(10)->create();
        $response = $this->get('/api/events/'.$events->first()->id);
        $response->assertStatus(200);
    }

    public function testRegisterToValidEvent()
    {
        $events = Event::factory()->state(['closing_at' => Carbon::now()->addDay()])->hasImage(1)->count(10)->create();
        $response = $this->post('/api/events/'.$events->first()->id.'/register', [
            'firstname' => 'laggoune',
            'lastname' => 'walid',
            'email' => 'walid@mail.com',
            'phone_number' => '0555555555',
            'id_card_number' => '123951357',
            'are_you_student' => 'yes',
            'motivation' => 'simple motivation',
            'is_usthb' => 'yes',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('event_registrations', 1);

        $response = $this->post('/api/events/'.$events->first()->id.'/register', [
            'firstname' => 'laggoune',
            'lastname' => 'walid',
            'email' => 'wali2d@mail.com',
            'phone_number' => '05552555555',
            'id_card_number' => '1239521357',
            'are_you_student' => 'yes',
            'motivation' => 'simple motivation',
            'study_field' => 'Math',
            'fonction' => 'backend dev',
            'is_usthb' => 'yes',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('event_registrations', 2);
        $this->assertEquals(EventRegistration::all()->last()->fonction, 'backend dev');
    }

    public function testRegisterToNonValidEvent()
    {
        $events = Event::factory()->state(['closing_at' => Carbon::now()->subDay()])->hasImage(1)->count(10)->create();
        $response = $this->post('/api/events/'.$events->first()->id.'/register', [
            'firstname' => 'laggoune',
            'lastname' => 'walid',
            'email' => 'walid@mail.com',
            'phone_number' => '0555555555',
            'id_card_number' => '123951357',
            'are_you_student' => 'yes',
            'motivation' => 'simple motivation',
            'is_usthb' => 'yes',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseCount('event_registrations', 0);
    }
}
