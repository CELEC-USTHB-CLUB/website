<?php

namespace App\Http\Resources;

use App\Models\ArcTeam;
use App\Http\Resources\ArcTeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArcRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parent = [];
        $parent['fullname'] = $this->fullname;
        $parent['team'] = new ArcTeamResource($this->whenLoaded('team'));
        return $parent;
    }
}
