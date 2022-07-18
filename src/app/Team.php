<?php

namespace App;

use App\Models\TeamImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['fullname', 'about', 'email', 'linked_in'];

    public $allow_export_all = false;

    public function image()
    {
        return $this->hasOne(TeamImage::class);
    }
}
