<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Http\Requests\Api\V1\Comment\UpdateCommentRequest;
use App\Http\Resources\V1\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(Blog $blog)
    {
        return CommentResource::collection($blog->comments()->with('user')->paginate(25));
    }

    public function store(StoreCommentRequest $request, Blog $blog)
    {
        $comment = $this->commentRepository->create([
            'blog_id' => $blog->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id,
        ]);

        return new CommentResource($comment);
    }

    public function show(Comment $comment)
    {
        return new CommentResource($comment->load('user'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $this->commentRepository->update($comment->id, $request->validated());

        return new CommentResource($comment->fresh());
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->commentRepository->delete($comment->id);

        return response()->noContent();
    }
}