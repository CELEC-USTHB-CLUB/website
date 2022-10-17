<?php

namespace App\Http\Livewire;

use App\Member;
use Livewire\Component;
use App\Traits\Batchable;
use App\Jobs\BadgeGeneratorJob;
use Illuminate\Support\Facades\Cache;

class BadgeGenerator extends Component
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

    public function batchFinished(): void
    {
        $this->downloadLink = Cache::get('download-badges-path');
        Cache::forget('download-badges-path');
    }
}
