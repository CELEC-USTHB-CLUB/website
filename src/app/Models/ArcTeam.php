<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArcTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code'
    ];

    public function users()
    {
        return $this->hasMany(ArcRegistration::class, 'team_id', 'code');
    }

}
