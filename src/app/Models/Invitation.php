<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'training_id', 'member_id'];

    public function invitationable(): MorphTo
    {
        return $this->morphTo();
    }
}
