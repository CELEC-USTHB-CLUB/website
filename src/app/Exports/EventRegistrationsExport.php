<?php

namespace App\Exports;

use App\TrainingRegistration;
use App\Models\EventRegistration;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventRegistrationsExport implements FromCollection, WithHeadings
{

    
    public function __construct(public int $id, public ?string $filters)
    {
    }

    public function headings(): array
    {
        return ['#', 'Event id', 'Firstname', 'Lastname', 'Email', 'Phone number', 'ID card number', 'Is student', 'Motivation', 'Study field', 'Fonction', 'Is usthb'];
    }

    public function collection()
    {
        return EventRegistration::select(['id', 'event_id', 'firstname', 'lastname', 'email', 'phone_number', 'id_card_number', 'is_student', 'motivation', 'study_field', 'fonction', 'is_usthb'])->where('event_id', $this->id)->get();
    }

}
