<?php

namespace App\Http\Livewire;

use App\Contracts\BatchTerminateable;
use App\Jobs\ExportMembersJob;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ExportMembers extends Component implements BatchTerminateable
{
    use Batchable;

    public $downloadLink;

    public function render()
    {
        return view('livewire.export-members');
    }

    public function export(): void
    {
        $this->batch(new ExportMembersJob());
    }

    public function batchFinished(Batch $bus): void
    {
        $this->downloadLink = Cache::get('exported-users-path');
        Cache::forget('exported-users-path');
    }
}
