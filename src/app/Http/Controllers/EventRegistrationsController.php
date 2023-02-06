<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventRegistrationsExport;

class EventRegistrationsController extends Controller
{
    public function export(Event $event, Request $request)
    {
        $filters = null;
        if ($request->has('filters')) {
            $filters = $request->filters;
        }

        return Excel::download(new EventRegistrationsExport($event->id, $filters), $event->name.'-registrations.xlsx');
    }
}
