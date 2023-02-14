<?php

namespace App\Models;

use App\TrainingRegistration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'invitation_id', 'training_id', 'checkedIn_at', 'checkedOut_at'];

    public $casts = [
        'checkedIn_at' => 'datetime:Y-m-d H:i:s',
        'checkedOut_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function member()
    {
        return $this->belongsTo(TrainingRegistration::class);
    }
}
