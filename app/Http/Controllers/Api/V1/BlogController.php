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

class BlogController extends Controller
{
    use ApiResponse;

    protected $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request)
    {
        try {
            // Query parameters
            $search = $request->query('search');
            $per_page = $request->query('per_page', 10);
            $created_by = $request->query('created_by');
            $relationsParam = $request->query('with'); // e.g. category,user,tags

            // Initialize query
            $query = Blog::query();

            // Search filter
            if ($search) {
                $query->where('title', 'like', "%{$search}%");
            }

            // Created by filter
            if ($created_by) {
                $query->where('user_id', $created_by);
            }

            // Eager load relations safely
            if ($relationsParam) {
                $relations = explode(',', $relationsParam);
                // Optional: validate relation names here
                $query->with($relations);
            }

            // Active blogs only
            $query->active(); // assumes scopeActive() exists

            // Paginate
            $blogs = $query->paginate($per_page);

            // Return ResourceCollection (pagination-safe)
            return BlogResource::collection($blogs)
                ->additional([
                    'success' => true,
                    'message' => $blogs->isEmpty() ? 'No blogs found' : 'Blogs fetched successfully'
                ]);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }



    public function store(StoreBlogRequest $request)
    {
        try {
            $data = $request->validated();

            $blog = Blog::create($data);
            

            // if ($request->tags) {
            //     $this->blogRepository->attachTags($blog, $request->tags);
            // }
            // return (new BlogResource($blog->load(['tags', 'user'])))->additional($this->StatusSuccess([], 'Data Store Successfuly'));
            $data = $blog->load(['tags', 'user']);
            return $this->successResponse($data, 'Data Store Successfuly');
        } catch (\Exception $execption) {
            // return $this->StatusError($execption->getMessage());
            return $this->errorResponse($execption->getMessage());
        }
    }

    public function show(Blog $blog)
    {
        try {
            return new BlogResource($blog->load(['category', 'tags', 'comments', 'user']));

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
