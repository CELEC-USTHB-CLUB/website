<?php

namespace App;

use App\Cv;
use App\Exports\MemberExport;
use App\Traits\RelationshipsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model {
    use HasFactory;
    use RelationshipsTrait;

    public $allow_export_all = true;
    public $export_handler = MemberExport::class;

    protected $fillable = [
        "fullname",
        "email",
        "is_usthb_student",
        "study_level",
        "study_field",
        "projects",
        "phone",
        "course_title",
        "course_description",
        "linked_in",
    ];

    public function cv() {
        return $this->morphOne(Cv::class, "cvable");
    }

}
