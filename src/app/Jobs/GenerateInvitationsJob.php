<?php

namespace App\Jobs;

use App\Imports\UsersImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class GenerateInvitationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Model $model, public string $path, public ?string $templatePath)
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
        if ($this->templatePath !== null) {
            $templatePath = storage_path('app/'.$this->templatePath);
        } else {
            $templatePath = null;
        }
        Excel::import(new UsersImport($this->model, $templatePath), storage_path('app/'.$this->path));
    }
}
