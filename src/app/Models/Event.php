<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'closing_at', 'is_closed', 'location'];

    protected $casts = [
        'closing_at' => 'datetime:Y-m-d h:i',
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
}
