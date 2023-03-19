<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members_arc extends Model
{
    use HasFactory;

    protected $hidden = ['password'];

    protected $table = 'members_arc';

    // hadi lazm f laravel
    protected $fillable = [
        'id_team',
        'full_name',
        'email',
        'password',
        'telephone',
        'etudiant',
        'fonction',
        'lien_git_hub',
        'lien_linked_in',
        'skills',
        'proj',
        'motivation',
    ];

    public function team()
    {
        return $this->hasOne(Team_arc::class);
    }
}
