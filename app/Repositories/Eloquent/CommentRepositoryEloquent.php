<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;

class CommentRepositoryEloquent extends BaseRepositoryEloquent implements CommentRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }
}