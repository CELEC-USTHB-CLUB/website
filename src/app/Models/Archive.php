<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = ['training_id', 'path'];

    public function archiveable(): MorphTo
    {
        return $this->morphTo();
    }
}
