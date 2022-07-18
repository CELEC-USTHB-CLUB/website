<?php

namespace App\Actions;

use App\Member;
use App\Training;
use App\TrainingRegistration;

class TrainingRegistrationAction
{
    public function handle(
        Training $training,
        string $fullname,
        string $email,
        string $registration_number,
        string $phone,
        string $is_celec_memeber,
        string $study_level,
        string $study_field,
        string $course_goals
    ): TrainingRegistration {
        if ($is_celec_memeber === 'yes') {
            $is_celec_memeber = (Member::where('email', $email)->count() > 0) ? true : false;
        } else {
            $is_celec_memeber = false;
        }

        return TrainingRegistration::create([
            'training_id' => $training->id,
            'fullname' => $fullname,
            'email' => $email,
            'registration_number' => $registration_number,
            'phone' => $phone,
            'is_celec_memeber' => $is_celec_memeber,
            'study_level' => $study_level,
            'study_field' => $study_field,
            'course_goals' => $course_goals,
        ]);
    }
}
