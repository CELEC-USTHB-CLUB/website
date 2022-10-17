<?php

namespace App\Models;

use App\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'invitation_id', 'paper_code', 'checkin_code', 'checkout_code'];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
