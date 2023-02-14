<?php

namespace App\Jobs;

use App\Exports\CheckExport;
use App\Training;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

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
        Excel::store(new CheckExport($this->training->id), 'public/checks-'.$this->training->id.'-export.xlsx');
        Cache::put('training-checks-excel-'.$this->training->id, 'checks-'.$this->training->id.'-export.xlsx');
    }
}
