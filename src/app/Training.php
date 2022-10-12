<?php

namespace App;

use Carbon\Carbon;
use App\Models\Archive;
use App\Models\Invitation;
use Illuminate\Support\Str;
use App\Models\TrainingImage;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
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

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function archive()
    {
        return $this->hasOne(Archive::class);
    }

}
