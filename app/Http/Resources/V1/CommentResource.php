<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Nested\BlogResource;
use App\Http\Resources\V1\Nested\UserResource;
use App\Http\Resources\V1\Nested\CommentResource as NestedCommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'blog' => new BlogResource($this->whenLoaded('blog')),
            'user' => new UserResource($this->whenLoaded('user')),
            'parent_id' => $this->parent_id,
            'replies' => NestedCommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
