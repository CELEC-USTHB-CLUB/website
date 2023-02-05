<?php

namespace App\Http\Livewire;

use App\Contracts\BatchTerminateable;
use App\Jobs\BadgeGeneratorJob;
use App\Member;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class BadgeGenerator extends Component implements BatchTerminateable
{
    use Batchable;

    public $downloadLink;

    public function render()
    {
        return view('livewire.badge-generator');
    }

    public function generate()
    {
        $this->batch(new BadgeGeneratorJob(Member::all()));
    }

    public function batchFinished(Batch $bus): void
    {
        $this->downloadLink = Cache::get('download-badges-path');
        Cache::forget('download-badges-path');
    }
}
