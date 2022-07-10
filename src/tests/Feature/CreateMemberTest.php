<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CreateMemberTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreatingMember() {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->create('cv.pdf', 100);
        $response = $this->post('/api/member/create', [
            "fullname" => "test test",
            "email" => "test@test.com",
            "birthdate" => "30-11-2000",
            "registration_number" => "123456789",
            "is_usthb_student" => "yes",
            "study_level" => "x",
            "study_field" => "x",
            "projects" => "x",
            "intersted_in" => "x",
            "skills" => "x",
            "cv" => "x",
            "other_clubs_experience" => "x",
            "linked_in" => "x",
            "motivation" => "x",
            "cv" => $file
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('members', 1);
    }

    public function testValidationIsWorking() {
        $response = $this->post('/api/member/create');
        $response->assertStatus(302);
    }

}
