<?php

namespace App\Http\Livewire;

use App\Training;
use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use App\Contracts\BatchTerminateable;
use Illuminate\Support\Facades\Cache;
use App\Jobs\GenerateTrainingChecksJob;

class ExportChecks extends Component implements BatchTerminateable
{
    use Batchable;

    public $training_id;

    public $checksZipPath;

    public function mount(int $id)
    {
        $this->training_id = $id;
        $training = Training::findOrFail($this->training_id);
    }

    public function render()
    {
        return view('livewire.export-checks');
    }

    public function submit(): void
    {
        $training = Training::findOrFail($this->training_id);
        $this->batch(
            new GenerateTrainingChecksJob($training),
        );
    }

    public function batchFinished(Batch $bus): void
    {
        $this->checksZipPath = Cache::get('training-checks-excel-'.$this->training_id);
        Cache::forget('training-checks-excel-'.$this->training_id);
    }

}
