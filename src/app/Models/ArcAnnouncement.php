<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArcAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'url', 'cover'];

}
