<?php

namespace App\Http\Livewire;

use App\Training;
use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use App\Traits\HasInvitation;
use Livewire\WithFileUploads;
use App\Contracts\BatchTerminateable;
use Illuminate\Database\Eloquent\Model;

class GenerateInvitation extends Component implements BatchTerminateable
{
    use WithFileUploads, Batchable, HasInvitation;

    public $excel;

    public $model_id;

    public $model;

    public $invitationsZipPath;

    public function mount(int $id, Model $model)
    {
        $this->model_id = $id;
        $this->model = $model;
        $model = $model::findOrFail($id);
        $this->invitationsZipPath = $model->archive()->latest()->get()->first()?->path;
    }

    public function render()
    {
        return view('livewire.generate-invitation');
    }

    public function submit(): void
    {
        $this->validate([
            'excel' => 'max:1024|mimes:xlsx',
        ]);
        $model = $this->model::findOrFail($this->model_id);
        $this->generate($model);
    }

    public function batchFinished(Batch $bus): void
    {
        $model = $this->model::findOrFail($this->model_id);
        $this->invitationsZipPath = $model->archive()->latest()->get()->first()->path;
    }
}
