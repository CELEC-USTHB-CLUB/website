<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team_arc extends Model
{
    use HasFactory;

    protected $table = 'team_arc';

    protected $fillable = [
        'tid',
        'nom_team',
        'region_team',
        'nbr_team',
        'accepted_team',
    ];

    public function membres()
    {
        return $this->hasMany(Members_arc::class);
    }
}
