<?php

namespace App\Traits;

use App\Jobs\GenerateInvitationsJob;
use Illuminate\Database\Eloquent\Model;

trait HasInvitation
{
    public function generate(Model $model)
    {
        $path = $this->excel->store('uploaded-accepted-users');
        $this->batch(
            new GenerateInvitationsJob($model, $path),
        );
    }
}
