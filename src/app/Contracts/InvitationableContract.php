<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface InvitationableContract
{
    public function getTitle(): string;

    public function getStartDate(): string;

    public function getLocation(): string;

    public function archive(): MorphOne;

    public function invitations(): MorphMany;

    public function checks(): MorphMany;
}
