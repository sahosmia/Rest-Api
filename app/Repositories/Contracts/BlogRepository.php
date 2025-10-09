<?php

namespace App\Repositories\Contracts;

interface BlogRepository extends BaseRepository
{
    public function attachTags($blog, array $tags);
    public function syncTags($blog, array $tags);
}