<?php

namespace App;

use App\Contracts\InvitationableContract;
use App\Models\Archive;
use App\Models\Certification;
use App\Models\CertificationZip;
use App\Models\Check;
use App\Models\Invitation;
use App\Models\TrainingImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Training extends Model implements InvitationableContract
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'slug', 'description', 'tags', 'closing_inscription_at', 'is_closed', 'starting_at', 'ending_at', 'location'];

    public $allow_export_all = true;

    protected $casts = [
        'closing_inscription_at' => 'datetime:Y-m-d h:i',
        'tags' => 'json',
        'starting_at' => 'datetime: Y-m-d',
        'ending_at' => 'datetime: Y-m-d',
    ];

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['cover'] = $this->image?->path;

        return $array;
    }

    protected static function boot()
    {
        parent::boot();
        Training::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function image()
    {
        return $this->hasOne(TrainingImage::class);
    }

    public function isClosed(): bool
    {
        return ($this->is_closed) ? true : Carbon::now()->greaterThanOrEqualTo($this->closing_inscription_at);
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class);
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

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function certificationZip()
    {
        return $this->hasMany(CertificationZip::class);
    }

    public function getTitle(): string
    {
        return $this->title;
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
