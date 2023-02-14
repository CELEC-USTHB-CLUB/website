<?php

namespace App\Jobs;

use App\Training;
use App\Exports\CheckExport;
use App\Imports\CheckImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerateTrainingChecksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Training $training)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new CheckExport($this->training->id), 'check-'.$this->training->id.'-export.xlsx');
        Cache::put('training-checks-excel-'.$this->training->id, 'xxx');
    }
}
