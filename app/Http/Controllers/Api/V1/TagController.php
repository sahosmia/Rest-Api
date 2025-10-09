<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepository;

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        return TagResource::collection($this->tagRepository->all());
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->tagRepository->create($request->validated());
        return new TagResource($tag);
    }

    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $updatedTag = $this->tagRepository->update($tag->id, $request->validated());
        return new TagResource($updatedTag);
    }

    public function destroy(Tag $tag)
    {
        $this->tagRepository->delete($tag->id);
        return response()->noContent();
    }
}