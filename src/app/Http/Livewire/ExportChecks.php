<?php

namespace App\Http\Livewire;

use App\Contracts\BatchTerminateable;
use App\Jobs\GenerateTrainingChecksJob;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ExportChecks extends Component implements BatchTerminateable
{
    use Batchable;

    public $model_id;

    public $model;

    public $checksZipPath;

    public function mount(int $id, Model $model)
    {
        $this->model_id = $id;
        $this->model = $model;
        $model = $this->model::findOrFail($this->model_id);
    }

    public function render()
    {
        return view('livewire.export-checks');
    }

    public function submit(): void
    {
        $model = $this->model::findOrFail($this->model_id);
        $this->batch(
            new GenerateTrainingChecksJob($model),
        );
    }

    public function batchFinished(Batch $bus): void
    {
        $this->checksZipPath = Cache::get('training-checks-excel-'.$this->model_id);
        Cache::forget('training-checks-excel-'.$this->model_id);
    }
}
