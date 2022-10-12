<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invitation_id', 'paper_code', 'checkin_code', 'checkout_code'];
}
