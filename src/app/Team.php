<?php

namespace App;

use App\Models\TeamImage;
use App\Models\ArcRegistration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
