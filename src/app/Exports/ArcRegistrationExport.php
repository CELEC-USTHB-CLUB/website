<?php

namespace App\Exports;

use App\Models\ArcRegistration;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArcRegistrationExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ArcRegistration::all();
    }

    public function headings(): array
    {
        return ['ID #', 'Fullname', 'Team name', 'Team code', 'Wilaya', 'Email', 'Phone', 'Is Student', 'Tshirt', 'Job', 'Linkedin or github', 'id_card_url', 'need hosting', 'skills', 'projects', 'motivation', 'created_at'];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->fullname,
            $user->team->title,
            $user->team->code,
            $user->wilaya,
            $user->email,
            $user->phone,
            ($user->is_student) ? "Yes" : "No",
            $user->tshirt,
            $user->job,
            $user->linkedIn_github,
            url("storage/$user->id_card_path"),
            ($user->need_hosting) ? "Yes" : "No",
            $user->skills,
            $user->projects,
            $user->motivation,
            $user->created_at->format("Y-m-d H:i:s"),
        ];
    }

}
