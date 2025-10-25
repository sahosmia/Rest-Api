<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Blog\StoreBlogRequest;
use App\Http\Requests\Api\V1\Blog\UpdateBlogRequest;
use App\Http\Resources\V1\BlogCollection;
use App\Http\Resources\V1\BlogResource;
use App\Models\Blog;
use App\Repositories\Contracts\BlogRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    use ApiResponse;

    public function __construct(protected BlogRepository $blogRepository)
    {
        //
    }

    public function index(Request $request)
    {
        try {
            $blogs = $this->blogRepository->paginate(
                $request->query('per_page', 10),
                $request->query('search'),
                $request->query('created_by'),
                $request->query('with')
            );
            return new BlogCollection($blogs);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve blogs.');
        }
    }

    public function store(StoreBlogRequest $request)
    {
        try {
            $blog = $this->blogRepository->create($request->validated());
            if ($request->has('tags')) {
                $this->blogRepository->attachTags($blog, $request->tags);
            }
            return $this->successResponse(new BlogResource($blog->load(['tags', 'user'])), 'Blog created successfully.', 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to create blog.');
        }
    }

    public function show(Blog $blog)
    {
        try {
            return $this->successResponse(new BlogResource($blog->load(['category', 'tags', 'comments', 'user'])), 'Blog retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve blog.');
        }
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        try {
            $updatedBlog = $this->blogRepository->update($blog->id, $request->validated());
            if ($request->has('tags')) {
                $this->blogRepository->syncTags($updatedBlog, $request->tags);
            }
            return $this->successResponse(new BlogResource($updatedBlog->load('tags')), 'Blog updated successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to update blog.');
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $this->blogRepository->delete($blog->id);
            return $this->successResponse(null, 'Blog deleted successfully.', 204);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to delete blog.');
        }
    }

    public function list()
    {
        try {
            $data = $this->blogRepository->allForList();
            return $this->successResponse($data, 'Blogs list retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve blogs list.');
        }
    }
}
