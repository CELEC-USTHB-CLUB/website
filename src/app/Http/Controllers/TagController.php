<?php

namespace App\Http\Controllers;

use App\Http\Resources\Tag as TagResource;
use App\Tag;

class TagController extends Controller
{
    public function get()
    {
        return TagResource::collection(Tag::all());
    }
}
