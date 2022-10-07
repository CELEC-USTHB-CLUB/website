<?php

namespace App;

use App\Models\Invitation;
use App\Models\TrainingImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

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


    public function invitations(): void
    {
        $this->hasMany(Invitation::class);
    }

}
