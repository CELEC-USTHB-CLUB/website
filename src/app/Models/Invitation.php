<?php

namespace App\Models;

use App\Training;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'training_id', 'member_id'];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

}
