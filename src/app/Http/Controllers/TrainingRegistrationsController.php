<?php

namespace App\Http\Controllers;

use App\Exports\TrainingRegistrationsExport;
use App\Training;
use Maatwebsite\Excel\Facades\Excel;

class TrainingRegistrationsController extends Controller
{
    public function export(Training $training)
    {
        return Excel::download(new TrainingRegistrationsExport($training->id), $training->slug.'-registrations.xlsx');
    }
}
