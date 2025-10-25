<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentRepositoryEloquent extends BaseRepositoryEloquent implements CommentRepository
{
    protected array $with = ['user', 'blog'];

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
        } else {
            $query->with($this->with);
        }

        return $query->paginate($perPage);
    }

    public function paginateForBlog(Blog $blog, $perPage = 10, $with = null)
    {
        $query = $blog->comments();

        if ($with) {
            $relations = explode(',', $with);
            $query->with($relations);
        } else {
            $query->with($this->with);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return parent::create($data)->load($this->with);
    }

    public function with(array $with)
    {
        $this->with = $with;
        return $this;
    }
}
