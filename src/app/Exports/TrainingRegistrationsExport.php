<?php

namespace App\Exports;

use App\TrainingRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingRegistrationsExport implements FromCollection, WithHeadings
{
    public function __construct(public int $id, public ?string $filters)
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
            'Is Celec member',
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
        if ($this->filters !== null) {
            return $this->generateWithFilters();
        }

        return TrainingRegistration::select(['id', 'training_id', 'fullname', 'email', 'phone', 'is_celec_memeber', 'registration_number', 'study_level', 'study_field', 'course_goals', 'created_at'])->where('training_id', $this->id)->get();
    }

    public function generateWithFilters()
    {
        $registrations = TrainingRegistration::select(['id', 'training_id', 'fullname', 'email', 'phone', 'is_celec_memeber', 'registration_number', 'study_level', 'study_field', 'course_goals', 'created_at'])->where('training_id', $this->id);
        $filtersLines = explode('/', $this->filters);

        $fillable = (new TrainingRegistration())->getFillable();
        array_push($fillable, 'created_at');

        foreach ($filtersLines as $filterLine) {
            $filterKeyValue = explode('=', $filterLine);
            if (count($filterKeyValue) === 2) {
                $columnName = str_replace(' ', '_', strtolower($filterKeyValue[0]));
                if ($columnName === 'is_celec_member') {
                    $columnName = 'is_celec_memeber';
                }
                if (in_array($columnName, $fillable)) {
                    if (strtolower($filterKeyValue[1]) === 'desc' or strtolower($filterKeyValue[1]) === 'asc') {
                        $registrations = $registrations->orderBy($columnName, $filterKeyValue[1]);
                    } elseif (str_contains($filterKeyValue[1], ',')) {
                        $arrayOfValues = explode(',', $filterKeyValue[1]);
                        $registrations = $registrations->whereIn($columnName, $arrayOfValues);
                    } else {
                        $registrations = $registrations->where($columnName, $filterKeyValue[1]);
                    }
                }
            }
        }

        return $registrations->get();
    }
}
