<?php

namespace App\Http\Livewire;

use Throwable;
use App\Training;
use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Bus;
use App\Jobs\GenerateInvitationsJob;
use App\Contracts\BatchTerminateable;

class GenerateInvitation extends Component implements BatchTerminateable
{
    use WithFileUploads, Batchable;

    public $excel;

    public $training_id;

    public $invitationsZipPath;

    public function mount(int $id)
    {
        $this->training_id = $id;
        $training = Training::findOrFail($this->training_id);
        $this->invitationsZipPath = $training->archive()->latest()->get()->first()?->path;
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
        $training = Training::findOrFail($this->training_id);
        $path = $this->excel->store('uploaded-accepted-users');
        if ($this->batchId !== null) {
            $batch = Bus::findBatch($this->batchId);
            $batch->cancel();
            $this->batchId = null;
            $this->finished = false;
        }
        $this->batch(
            new GenerateInvitationsJob($training, $path),
        );

        $this->batchId = $batch->id;
    }



    public function batchFinished(Batch $bus) : void
    {
        $training = Training::findOrFail($this->training_id);
        $this->invitationsZipPath = $training->archive()->latest()->get()->first()->path;
    }
}
