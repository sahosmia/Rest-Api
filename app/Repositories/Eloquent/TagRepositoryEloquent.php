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

    public function paginate($perPage = 10, $search = null, $with = null)
    {
        $query = $this->model->query();

        if ($with) {
            $relations = explode(',', $with);
            $query->with($relations);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
