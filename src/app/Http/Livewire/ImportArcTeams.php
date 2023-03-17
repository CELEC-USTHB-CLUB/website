<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\Batchable;
use Illuminate\Bus\Batch;
use Livewire\WithFileUploads;
use App\Contracts\BatchTerminateable;
use Illuminate\Support\Facades\Cache;
use App\Jobs\GenerateArcInvitationsJob;

class ImportArcTeams extends Component implements BatchTerminateable
{
    use Batchable, WithFileUploads;

    public $downloadLink;

    public $excel;

    public $template;

    public function render()
    {
        return view('livewire.import-arc-teams');
    }

    public function import(): void
    {
        $this->validate([
            'excel' => 'required|max:1024|mimes:xlsx',
            'template' => 'required|max:10024|mimes:pdf',
        ]);

        $usersListFilePath = $this->excel->store('uploaded-accepted-users');
        $templatePath = $this->template->store('uploaded-accepted-users');

        $this->batch(
            new GenerateArcInvitationsJob($usersListFilePath, $templatePath),
        );

    }

    public function batchFinished(Batch $bus): void
    {
        $this->downloadLink = Cache::get('exported-arc-invitations-path');
        Cache::forget('exported-arc-invitations-path');
    }
}
