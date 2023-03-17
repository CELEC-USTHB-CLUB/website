<?php

namespace App\Http\Resources;

use App\Models\ArcRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ArcTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);
        $parent['team_users_of_authenticated_user'] = ArcRegistrationResource::collection($this->users()->whereNot('arc_registrations.id', Auth::user()->id)->get());
        return $parent;
    }
}
