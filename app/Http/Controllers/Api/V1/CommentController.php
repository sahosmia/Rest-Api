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

class CommentController extends Controller
{
    use ApiResponse;

    public function __construct(protected CommentRepository $commentRepository)
    {
        //
    }

    public function index(Request $request, Blog $blog)
    {
        $comments = $this->commentRepository->paginateForBlog(
            $blog,
            $request->query('per_page', 10),
            $request->query('with')
        );
        return new CommentCollection($comments);
    }

    public function store(StoreCommentRequest $request, Blog $blog)
    {
        $data = $request->validated();
        $data['blog_id'] = $blog->id;

        $comment = $this->commentRepository->create($data);
        return $this->successResponse(new CommentResource($comment), 'Comment created successfully.', 201);
    }

    public function show(Comment $comment)
    {
        return $this->successResponse(new CommentResource($comment->load(['user', 'blog'])), 'Comment retrieved successfully.');
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $updatedComment = $this->commentRepository->update($comment->id, $request->validated());
        return $this->successResponse(new CommentResource($updatedComment), 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $this->commentRepository->delete($comment->id);
        return response()->noContent();
    }
}
