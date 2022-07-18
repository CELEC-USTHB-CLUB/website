<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    public function all()
    {
        return EventResource::collection(Event::orderByDesc('id')->limit(3)->get());
    }
}
