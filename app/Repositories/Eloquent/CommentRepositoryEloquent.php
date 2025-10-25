<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;

class CommentRepositoryEloquent extends BaseRepositoryEloquent implements CommentRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage = 10, $search = null, $with = null)
    {
        $query = $this->model->query();

        if ($with) {
            $relations = explode(',', $with);
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }

    public function paginateForBlog(Blog $blog, $perPage = 10, $with = null)
    {
        $query = $blog->comments();

        if ($with) {
            $relations = explode(',', $with);
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }
}
