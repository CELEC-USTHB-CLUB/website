<?php

namespace App\Http\Livewire;

use App\Jobs\GenerateInvitationsJob;
use App\Training;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class GenerateInvitation extends Component
{
    use WithFileUploads;

    public $excel;

    public $training_id;

    public $batchId;

    public $finished = false;

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
        $batch = Bus::batch([
            new GenerateInvitationsJob($training, $path),
        ])->allowFailures()->catch(function (Batch $batch, Throwable $e) {
            dump($e->getMessage());
        })->dispatch();

        $this->batchId = $batch->id;
    }

    public function getBatchProperty(): ?Batch
    {
        return ($this->batchId) ? Bus::findBatch($this->batchId) : null;
    }

    public function checkStatus()
    {
        $finished =  Bus::findBatch($this->batchId)->finished();
        if ($finished) {
            $training = Training::findOrFail($this->training_id);
            $this->invitationsZipPath = $training->archive()->latest()->get()->first()->path;
        }
        $this->finished = $finished;
    }
}
