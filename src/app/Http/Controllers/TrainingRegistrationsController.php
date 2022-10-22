<?php

namespace App\Http\Controllers;

use App\Exports\TrainingRegistrationsExport;
use App\Training;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
