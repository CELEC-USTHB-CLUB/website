<?php

namespace App\Models;

use App\Training;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = ['fullname', 'signature'];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

}
