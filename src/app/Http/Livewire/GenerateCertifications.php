<?php

namespace App\Http\Livewire;

use App\Training;
use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Bus;
use App\Contracts\BatchTerminateable;
use App\Jobs\GenerateCertificationsJob;

class GenerateCertifications extends Component implements BatchTerminateable
{
    use WithFileUploads, Batchable;

    public $excel;

    public $certification;

    public $certificationsZipPath;

    public $training_id;

    public function mount(int $id)
    {
        $this->training_id = $id;
        $training = Training::findOrFail($this->training_id);
        $this->certificationsZipPath = $training->certificationZip()->latest()->get()->first()?->path;
    }

    public function render()
    {
        return view('livewire.generate-certifications');
    }

    public function submit(): void
    {
        $this->validate([
            'excel' => 'max:1024|mimes:xlsx',
            'certification' => 'max:5024|mimes:pdf',
        ]);
        $usersPath = $this->excel->store('uploaded-certefications-users');
        $certificationTemplatePath = $this->certification->store('uploaded-certifications-templates');
        $training = Training::findOrFail($this->training_id);
        $this->batch(
            new GenerateCertificationsJob($training, $usersPath, $certificationTemplatePath)
        );
    }

    public function batchFinished(Batch $bus) : void
    {
        $training = Training::findOrFail($this->training_id);
        $this->certificationsZipPath = $training->certificationZip()->latest()->get()->first()?->path;
    }
}
