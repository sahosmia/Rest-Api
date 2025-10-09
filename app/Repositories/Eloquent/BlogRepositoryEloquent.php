<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepository;

class BlogRepositoryEloquent extends BaseRepositoryEloquent implements BlogRepository
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function attachTags($blog, array $tags)
    {
        $blog->tags()->attach($tags);
    }

    public function syncTags($blog, array $tags)
    {
        $blog->tags()->sync($tags);
    }
}