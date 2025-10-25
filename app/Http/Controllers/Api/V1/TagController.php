<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tag\StoreTagRequest;
use App\Http\Requests\Api\V1\Tag\UpdateTagRequest;
use App\Http\Resources\V1\TagCollection;
use App\Http\Resources\V1\TagResource;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponse;

    public function __construct(protected TagRepository $tagRepository)
    {
        //
    }

    public function index(Request $request)
    {
        $tags = $this->tagRepository->paginate(
            $request->query('per_page', 10),
            $request->query('search'),
            $request->query('with')
        );
        return new TagCollection($tags);
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->tagRepository->create($request->validated());
        return $this->successResponse(new TagResource($tag), 'Tag created successfully.', 201);
    }

    public function show(Tag $tag)
    {
        return $this->successResponse(new TagResource($tag), 'Tag retrieved successfully.');
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $updatedTag = $this->tagRepository->update($tag->id, $request->validated());
        return $this->successResponse(new TagResource($updatedTag), 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $this->tagRepository->delete($tag->id);
        return response()->noContent();
    }

    public function list()
    {
        $data = $this->tagRepository->allForList('name');
        return $this->successResponse($data, 'Tags list retrieved successfully.');
    }
}
