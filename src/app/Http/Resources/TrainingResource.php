<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'tags' => $this->tags,
            'description' => $this->description,
            'is_closed' => $this->isClosed(),
            'cover' => $this->image->path,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
