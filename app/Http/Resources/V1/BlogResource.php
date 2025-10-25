<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Nested\CategoryResource;
use App\Http\Resources\V1\Nested\UserResource;
use App\Http\Resources\V1\Nested\TagResource;
use App\Http\Resources\V1\Nested\CommentResource;
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
            'slug' => $this->slug,
            'description' => $this->description,
            'photo' => $this->photo,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'author' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
