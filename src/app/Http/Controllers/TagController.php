<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Tag;

class TagController extends Controller
{
    public function get()
    {
        return new TagCollection(Tag::all());
    }
}
