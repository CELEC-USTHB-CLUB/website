<?php

namespace App\Http\Livewire;

use App\Member;
use Livewire\Component;
use App\Traits\Batchable;
use App\Jobs\BadgeGeneratorJob;
use Illuminate\Support\Facades\Bus;

class BadgeGenerator extends Component
{

    use Batchable;

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
        // dump('batch end');
    }

}
