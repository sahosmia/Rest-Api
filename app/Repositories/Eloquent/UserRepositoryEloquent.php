<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;

class UserRepositoryEloquent extends BaseRepositoryEloquent implements UserRepository
{
    public function __construct(User $model)
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
