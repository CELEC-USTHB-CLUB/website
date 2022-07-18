<?php

namespace App;

use App\Image;
use App\Models\TeamImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model {
    use HasFactory;

    protected $fillable = ["fullname", "about", "email", "linked_in"];

    public function image() {
        return $this->hasOne(TeamImage::class);
    }

}
