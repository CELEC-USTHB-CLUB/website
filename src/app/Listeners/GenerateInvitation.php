<?php

namespace App\Listeners;

use App\Imports\UsersImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;

class GenerateInvitation implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Excel::import(new UsersImport($event->training), storage_path('app/'.$event->uploadedExcelFile)); 
    }
}
