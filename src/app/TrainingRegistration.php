<?php

namespace App;

use App\Training;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRegistration extends Model {
    use HasFactory;

    protected $fillable = ['training_id', 'fullname', 'email', 'registration_number', 'phone', 'is_celec_memeber', 'study_level', 'study_field', 'course_goals'];

    public function course() {
        return $this->belongsTo(Training::class);
    }

}
