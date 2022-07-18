<?php

namespace App;

use App\Traits\RelationshipsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model {
    use HasFactory;
    use RelationshipsTrait;

    protected $fillable = ["path"];

    public function cvable() {
        return $this->morphTo();
    }

}
