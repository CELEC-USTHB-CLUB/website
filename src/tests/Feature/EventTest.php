<?php

namespace Tests\Feature;

use App\Training;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\TrainingRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

    public function testGettingSignleTraining()
    {
        $events = Event::factory()->hasImage(1)->count(10)->create();
        $response = $this->get('/api/events/'.$events->first()->id);
        $response->assertStatus(200);
    }

    public function testRegisterToValidTraining()
    {
        $trainings = Training::factory()->state(['closing_inscription_at' => Carbon::now()->addDay()])->hasImage(1)->count(10)->create();
        $response = $this->post('/api/trainings/'.$trainings->first()->slug.'/register', [
            'fullname' => 'test',
            'email' => 'test@gmail.com',
            'registration_number' => 'test',
            'phone' => 'test',
            'is_celec_memeber' => 'yes',
            'study_level' => 'test',
            'study_field' => 'test',
            'course_goals' => 'test',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('training_registrations', 1);
        $this->assertEquals(TrainingRegistration::all()->first()->is_celec_memeber, false);
    }

}
