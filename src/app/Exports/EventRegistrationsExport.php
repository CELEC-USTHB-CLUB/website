<?php

namespace App\Exports;

use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventRegistrationsExport implements FromCollection, WithHeadings
{
    public function __construct(public int $id, public ?string $filters)
    {
    }

    public function headings(): array
    {
        return ['#', 'Event id', 'Fullname', 'Email', 'Phone number', 'ID card number', 'Is student', 'Motivation', 'Study field', 'Fonction', 'Created at', 'Is usthb'];
    }

    public function collection()
    {
        return EventRegistration::select([
            'id', 
            'event_id', 
            DB::raw('CONCAT(firstname, " ",lastname)'),
            'email', 
            'phone_number', 
            'id_card_number', 
            'is_student', 
            'motivation', 
            'study_field', 
            'fonction', 
            'created_at',
            'is_usthb'
        ])->where('event_id', $this->id)->get();
    }
}
