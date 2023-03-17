<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use App\Contracts\BatchTerminateable;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ExportArcRegistrationsJob;

class ExportArcRegistrations extends Component implements BatchTerminateable
{
    use Batchable;

    public $downloadLink;

    public function render()
    {
        return view('livewire.export-arc-registrations');
    }

    public function export(): void
    {
        $this->batch(new ExportArcRegistrationsJob());
    }

    public function batchFinished(Batch $bus): void
    {
        $this->downloadLink = Cache::get('exported-arc-users-path');
        Cache::forget('exported-arc-users-path');
    }
}
