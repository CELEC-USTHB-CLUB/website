<?php

namespace App\Http\Livewire;

use App\Contracts\BatchTerminateable;
use App\Jobs\GenerateInvitationsJob;
use App\Training;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Livewire\Component;
use Livewire\WithFileUploads;

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
        $this->batch(
            new GenerateInvitationsJob($training, $path),
        );
    }

    public function batchFinished(Batch $bus): void
    {
        $training = Training::findOrFail($this->training_id);
        $this->invitationsZipPath = $training->archive()->latest()->get()->first()->path;
    }
}
