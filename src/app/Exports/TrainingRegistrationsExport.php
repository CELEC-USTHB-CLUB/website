<?php

namespace App\Exports;

use App\TrainingRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingRegistrationsExport implements FromCollection, WithHeadings
{
    public function __construct(public int $id)
    {
    }

    public function headings(): array
    {
        return [
            '#',
            'Training id',
            'Fullname',
            'email',
            'phone',
            'Is Celec memeber',
            'matricule',
            'study level',
            'study field',
            'Goals',
            'Registred at',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TrainingRegistration::select(['id', 'training_id', 'fullname', 'email', 'phone', 'is_celec_memeber', 'registration_number', 'study_level', 'study_field', 'course_goals', 'created_at'])->where('training_id', $this->id)->get();
    }
}
