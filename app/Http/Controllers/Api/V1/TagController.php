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
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    use ApiResponse;

    public function __construct(protected TagRepository $tagRepository)
    {
        //
    }

    public function index(Request $request)
    {
        try {
            $tags = $this->tagRepository->paginate(
                $request->query('per_page', 10),
                $request->query('search'),
                $request->query('with')
            );
            return new TagCollection($tags);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve tags.');
        }
    }

    public function store(StoreTagRequest $request)
    {
        try {
            $tag = $this->tagRepository->create($request->validated());
            return $this->successResponse(new TagResource($tag), 'Tag created successfully.', 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to create tag.');
        }
    }

    public function show(Tag $tag)
    {
        try {
            return $this->successResponse(new TagResource($tag), 'Tag retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve tag.');
        }
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $updatedTag = $this->tagRepository->update($tag->id, $request->validated());
            return $this->successResponse(new TagResource($updatedTag), 'Tag updated successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to update tag.');
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $this->tagRepository->delete($tag->id);
            return $this->successResponse(null, 'Tag deleted successfully.', 204);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to delete tag.');
        }
    }

    public function list()
    {
        try {
            $data = $this->tagRepository->allForList('name');
            return $this->successResponse($data, 'Tags list retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve tags list.');
        }
    }
}
