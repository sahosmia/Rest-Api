<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepository;

class TagRepositoryEloquent extends BaseRepositoryEloquent implements TagRepository
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }
}