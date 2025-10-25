<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Http\Requests\Api\V1\Comment\UpdateCommentRequest;
use App\Http\Resources\V1\CommentCollection;
use App\Http\Resources\V1\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    use ApiResponse;

    public function __construct(protected CommentRepository $commentRepository)
    {
        //
    }

    public function index(Request $request, Blog $blog)
    {
        try {
            $comments = $this->commentRepository->paginateForBlog(
                $blog,
                $request->query('per_page', 10),
                $request->query('with')
            );
            return new CommentCollection($comments);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve comments.');
        }
    }

    public function store(StoreCommentRequest $request, Blog $blog)
    {
        try {
            $data = $request->validated();
            $data['blog_id'] = $blog->id;
            $data['user_id'] = Auth::id();

            $comment = $this->commentRepository->create($data);
            return $this->successResponse(new CommentResource($comment), 'Comment created successfully.', 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to create comment.');
        }
    }

    public function show(Comment $comment)
    {
        try {
            return $this->successResponse(new CommentResource($comment->load('user', 'blog')), 'Comment retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve comment.');
        }
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            $this->authorize('update', $comment);
            $updatedComment = $this->commentRepository->update($comment->id, $request->validated());
            return $this->successResponse(new CommentResource($updatedComment), 'Comment updated successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to update comment.');
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $this->authorize('delete', $comment);
            $this->commentRepository->delete($comment->id);
            return $this->successResponse(null, 'Comment deleted successfully.', 204);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to delete comment.');
        }
    }
}
