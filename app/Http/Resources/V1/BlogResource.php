<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\TagResource;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category_id' => ['id' => optional($this->category)->id, 'title' => optional($this->category)->title],
            'description' => $this->description,
            'photo' => $this->photo,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'author' => new UserResource($this->whenLoaded('user')),
        ];
    }
}