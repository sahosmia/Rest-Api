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
        $blogs = $this->blogRepository->paginate(
            $request->query('per_page', 10),
            $request->query('search'),
            $request->query('created_by'),
            $request->query('with')
        );
        return new BlogCollection($blogs);
    }

    public function store(StoreBlogRequest $request)
    {
        $blog = $this->blogRepository->create($request->validated());
        return $this->successResponse(new BlogResource($blog), 'Blog created successfully.', 201);
    }

    public function show(Blog $blog)
    {
        return $this->successResponse(new BlogResource($blog->load(['category', 'tags', 'comments', 'user'])), 'Blog retrieved successfully.');
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $updatedBlog = $this->blogRepository->update($blog->id, $request->validated());
        return $this->successResponse(new BlogResource($updatedBlog), 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->blogRepository->delete($blog->id);
        return response()->noContent();
    }

    public function list()
    {
        $data = $this->blogRepository->allForList();
        return $this->successResponse($data, 'Blogs list retrieved successfully.');
    }
}
