<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use App\Jobs\ExportMembersJob;
use App\Contracts\BatchTerminateable;
use Illuminate\Support\Facades\Cache;

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
