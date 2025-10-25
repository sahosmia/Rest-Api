<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;

interface CommentRepository extends BaseRepository
{
    public function paginateForBlog(Blog $blog, $perPage = 10, $with = null);
}
