<?php

namespace App\Jobs;

use App\Imports\TrainingUsersImport;
use App\Training;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class GenerateCertificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Training $training, public string $excelPath, public $certificationTemplatePath)
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
        Excel::import(new TrainingUsersImport($this->training, storage_path('app/'.$this->certificationTemplatePath)), storage_path('app/'.$this->excelPath));
    }
}
