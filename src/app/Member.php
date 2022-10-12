<?php

namespace App;

use App\Exports\MemberExport;
use App\Models\MemberImage;
use App\Traits\RelationshipsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    use RelationshipsTrait;

    public $allow_export_all = true;

    public $export_handler = MemberExport::class;

    protected $fillable = [
        'fullname',
        'email',
        'birthdate',
        'registration_number',
        'is_usthb_student',
        'study_level',
        'study_field',
        'projects',
        'intersted_in',
        'skills',
        'other_clubs_experience',
        'linked_in',
        'motivation',
    ];

    public function getSkillsAttribute($value)
    {
        return json_decode($value);
    }

    public function cv()
    {
        return $this->morphOne(Cv::class, 'cvable');
    }

    public function image()
    {
        return $this->hasOne(MemberImage::class);
    }
}
