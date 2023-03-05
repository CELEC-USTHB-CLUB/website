<?php

namespace App\Http\Controllers;

use App\Actions\EventRegistrationAction;
use App\Http\Requests\EventRegistrationRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function all(): AnonymousResourceCollection
    {
        return EventResource::collection(Event::orderByDesc('id')->paginate(12));
    }

    public function get(Event $event): EventResource
    {
        return new EventResource($event);
    }

    public function register(
        Event $event,
        EventRegistrationRequest $request,
        EventRegistrationAction $eventRegistrationAction
    ): EventRegistration {
        if ($event->isClosed()) {
            abort(403, 'Registration expired');
        }

        return $eventRegistrationAction->handle(
            $event,
            $request->firstname,
            $request->lastname,
            $request->email,
            $request->phone_number,
            $request->id_card_number,
            $request->are_you_student,
            $request->motivation,
            ($request->has('study_field')) ? $request->study_field : null,
            ($request->has('fonction')) ? $request->fonction : null
        );
    }
}
