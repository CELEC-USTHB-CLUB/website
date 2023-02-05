<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function all() : AnonymousResourceCollection
    {
        return EventResource::collection(Event::orderByDesc('id')->paginate(12));
    }

    public function get(Event $event) : EventResource 
    {
        return new EventResource($event);
    }

}
