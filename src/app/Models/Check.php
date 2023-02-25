<?php

namespace App\Models;

use App\TrainingRegistration;
use App\Models\EventRegistration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Check extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'invitation_id', 'checkable_id', 'checkable_type', 'checkedIn_at', 'checkedOut_at'];

    public $casts = [
        'checkedIn_at' => 'datetime:Y-m-d H:i:s',
        'checkedOut_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function checkable()
    {
        return $this->morphTo();
    }

    public function trainingMember()
    {
        return $this->belongsTo(TrainingRegistration::class, 'member_id', 'id');
    }


    public function eventMember()
    {
        return $this->belongsTo(EventRegistration::class, 'member_id', 'id');
    }

}
