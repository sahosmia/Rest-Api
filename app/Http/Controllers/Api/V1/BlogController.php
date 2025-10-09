<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Blog\StoreBlogRequest;
use App\Http\Requests\Api\V1\Blog\UpdateBlogRequest;
use App\Http\Resources\V1\BlogResource;
use App\Models\Blog;
use App\Repositories\Contracts\BlogRepository;
use App\Traits\ApiStatus;

class BlogController extends Controller
{
    use ApiStatus;

    protected $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function index()
    {
        try {
            $datas = $this->blogRepository->all();
            return BlogResource::collection($datas)->additional($this->StatusResource());
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function store(StoreBlogRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $blog = $this->blogRepository->create($data);
            if ($request->tags) {
                $this->blogRepository->attachTags($blog, $request->tags);
            }
            return (new BlogResource($blog->load(['tags', 'user'])))->additional($this->StatusSuccess([], 'Data Store Successfuly'));
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function show(Blog $blog)
    {
        try {
            return (new BlogResource($blog->load('tags')))->additional($this->StatusResource());
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        try {
            $this->blogRepository->update($blog->id, $request->validated());
            if ($request->tags) {
                $this->blogRepository->syncTags($blog, $request->tags);
            }
            return $this->StatusSuccess($blog->fresh()->load('tags'), 'Data Update Successfuly');
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $this->blogRepository->delete($blog->id);
            return $this->StatusSuccess([], 'Data Delete Successfuly');
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }
}