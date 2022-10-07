<?php

namespace App\Http\Livewire;

use Throwable;
use App\Training;
use Livewire\Component;
use Illuminate\Bus\Batch;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Bus;
use App\Jobs\GenerateInvitationsJob;

class GenerateInvitation extends Component
{

    use WithFileUploads;

    public $excel;

    public $training_id;

    public $batchId;

    public $finished = false;

    public function mount(int $id)
    {
        $this->training_id = $id;
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
        $batch = Bus::batch([
            new GenerateInvitationsJob($training, $path)
        ])->allowFailures()->catch(function(Batch $batch, Throwable $e) {
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
        $this->finished = Bus::findBatch($this->batchId)->finished();
    }

}
