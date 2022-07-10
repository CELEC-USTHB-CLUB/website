<?php

namespace App;

use App\Cv;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model {
    use HasFactory;

    protected $fillable = [
        "fullname",
        "email",
        "birthdate",
        "registration_number",
        "is_usthb_student",
        "study_level",
        "study_field",
        "projects",
        "intersted_in",
        "skills",
        "other_clubs_experience",
        "linked_in",
        "motivation"
    ];

    public function getSkillsAttribute($value) {
        return json_decode($value);
    }

    public function cv() {
        return $this->morphOne(Cv::class, "cvable");
    }

}
