<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model {
    use HasFactory;

    protected $fillable = ["path"];

    public function cvable() {
        return $this->morphTo();
    }

}
