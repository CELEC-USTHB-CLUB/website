<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CreateTrainerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreatingMember() {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->create('cv.pdf', 100);
        $response = $this->post('/api/trainer/create', [
            "fullname" => "test",
            "email" => "test@test.com",
            "is_usthb_student" => "test",
            "study_level" => "test",
            "study_field" => "test",
            "projects" => "test",
            "phone" => "test",
            "course_title" => "test",
            "course_description" => "test",
            "linked_in" => "test",
            "cv" => $file
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('trainers', 1);
    }

    public function testValidationIsWorking() {
        $response = $this->post('/api/trainer/create');
        $response->assertStatus(302);
    }

}
