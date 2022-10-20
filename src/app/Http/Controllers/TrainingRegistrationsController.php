<?php

namespace App\Http\Controllers;

use App\Training;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingRegistrationsExport;

class TrainingRegistrationsController extends Controller
{
    public function export(Training $training, Request $request)
    {
        $filters = null;
        if ($request->has('filters')) {
            $filters = $request->filters;
        }
        return Excel::download(new TrainingRegistrationsExport($training->id, $filters), $training->slug.'-registrations.xlsx');
    }
}
