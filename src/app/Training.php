<?php

namespace App;

use App\Models\TrainingImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Training extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'slug', 'description', 'tags', 'closing_inscription_at'];

    protected $casts = [
        'closing_inscription_at' => 'datetime:Y-m-d h:i',
        'tags' => 'json',
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
        return Carbon::now()->greaterThanOrEqualTo($this->closing_inscription_at);
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class);
    }
}
