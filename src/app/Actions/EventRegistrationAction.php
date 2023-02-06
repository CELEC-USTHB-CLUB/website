<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\EventRegistration;

class EventRegistrationAction
{
    public function handle(
        Event $event,
        string $firstname,
        string $lastname,
        string $email,
        string $phone_number,
        string $id_card_number,
        string $are_you_student,
        string $motivation,
        string $is_usthb,
        ?string $study_field = null,
        ?string $fonction = null
    ): EventRegistration {
        return EventRegistration::create([
            'event_id' => $event->id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone_number' => $phone_number,
            'id_card_number' => $id_card_number,
            'is_student' => ($are_you_student == 'yes') ? true : false,
            'motivation' => $motivation,
            'study_field' => $study_field,
            'fonction' => $fonction,
            'is_usthb' => ($is_usthb == 'yes') ? true : false
        ]);
    }
}
