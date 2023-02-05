<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'firstname', 'lastname', 'email', 'phone_number', 'id_card_number', 'is_student', 'motivation', 'study_field', 'fonction'];

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
