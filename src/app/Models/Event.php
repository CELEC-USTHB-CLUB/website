<?php

namespace App\Models;

use App\Contracts\InvitationableContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Event extends Model implements InvitationableContract
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'closing_at', 'is_closed', 'location', 'starting_at'];

    protected $casts = [
        'closing_at' => 'datetime:Y-m-d h:i',
        'starting_at' => 'datetime: Y-m-d',
    ];

    public function image()
    {
        return $this->hasOne(EventImage::class);
    }

    public function isClosed(): bool
    {
        return ($this->is_closed) ? true : Carbon::now()->greaterThanOrEqualTo($this->closing_at);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
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

    public function getTitle(): string
    {
        return $this->name;
    }

    public function getStartDate(): string
    {
        return $this->starting_at;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
