<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateMemberTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreatingMember()
    {
        Storage::fake('cvs');
        Storage::fake('member_images');
        $file1 = UploadedFile::fake()->create('cv.pdf', 100);
        $file2 = UploadedFile::fake()->image('image.png', 100);
        $response = $this->post('/api/member/create', [
            'fullname' => 'test test',
            'email' => 'test@test.com',
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
            'cv' => $file1,
            'image' => $file2
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('members', 1);
    }

    public function testValidationIsWorking()
    {
        $response = $this->post('/api/member/create');
        $response->assertStatus(302);
    }
}
