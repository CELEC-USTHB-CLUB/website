<?php

namespace App\Traits;

use App\Jobs\GenerateInvitationsJob;
use Illuminate\Database\Eloquent\Model;

trait HasInvitation
{
    public function generate(Model $model, string $usersListFilePath, ?string $templatePath)
    {
        $this->batch(
            new GenerateInvitationsJob($model, $usersListFilePath, $templatePath),
        );
    }
}
