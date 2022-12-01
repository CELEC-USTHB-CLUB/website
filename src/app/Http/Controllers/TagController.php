<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Http\Resources\TagCollection;
use App\Http\Resources\Tag as TagResource;

class TagController extends Controller
{
    public function get()
    {
        return TagResource::collection(Tag::all());
    }
}
