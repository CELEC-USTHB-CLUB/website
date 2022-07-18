<?php

namespace Tests\Feature;

use App\Training;
use App\TrainingRegistration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    use DatabaseTransactions;

    public function testGet()
    {
        Training::factory()->hasImage(1)->count(10)->create();
        $response = $this->get('/api/trainings/get');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    public function testGettingSignleTraining()
    {
        $trainings = Training::factory()->hasImage(1)->count(10)->create();
        $response = $this->get('/api/trainings/'.$trainings->first()->slug);
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

    public function testRegisterToNonValidTest()
    {
        $trainings = Training::factory()->state(['closing_inscription_at' => Carbon::now()->subDay()])->hasImage(1)->count(10)->create();
        $response = $this->post('/api/trainings/'.$trainings->first()->slug.'/register', [
            'fullname' => 'test',
            'email' => 'test@gmail.com',
            'registration_number' => 'test',
            'phone' => 'test',
            'is_celec_memeber' => 'test',
            'study_level' => 'test',
            'study_field' => 'test',
            'course_goals' => 'test',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseCount('training_registrations', 0);
    }

    public function testRegisterAsClubMember()
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->create('cv.pdf', 100);
        $response = $this->post('/api/member/create', [
            'fullname' => 'test test',
            'email' => 'test@gmail.com',
            'birthdate' => '30-11-2000',
            'registration_number' => '123456789',
            'is_usthb_student' => 'yes',
            'study_level' => 'x',
            'study_field' => 'x',
            'projects' => 'x',
            'intersted_in' => 'x',
            'skills' => 'x',
            'cv' => 'x',
            'other_clubs_experience' => 'x',
            'linked_in' => 'x',
            'motivation' => 'x',
            'cv' => $file,
        ]);
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
        $this->assertEquals(TrainingRegistration::all()->first()->is_celec_memeber, true);
    }
}
