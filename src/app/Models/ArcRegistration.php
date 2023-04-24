<?php

namespace App\Models;

use App\Models\Check;
use App\Models\Archive;
use App\Models\Invitation;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\InvitationableContract;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArcRegistration extends Model implements InvitationableContract
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'wilaya',
        'fullname',
        'email',
        'phone',
        'is_student',
        'job',
        'linkedIn_github',
        'id_card_path',
        'need_hosting',
        'skills',
        'projects',
        'motivation',
        'team_id',
        'tshirt',
        'password',
        'is_accepted'
    ];

    protected $hidden = ['password'];

    public function team()
    {
        return $this->belongsTo(ArcTeam::class, 'team_id', 'code');
    }

    public function getTitle(): string
    {
        return 'ARC';        
    }

    public function getStartDate(): string
    {
        return '2023-04-29';   
    }

    public function getLocation(): string
    {
        return $this->wilaya;        
    }

    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }

    public function archive(): MorphOne
    {
        return $this->morphOne(Archive::class, 'archiveable');
    }

    public function checks(): MorphMany
    {
        return $this->morphMany(Check::class, 'checkable');
    }

}
