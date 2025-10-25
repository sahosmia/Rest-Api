<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepository;
use App\Traits\FileUploadTrait;

class BlogRepositoryEloquent extends BaseRepositoryEloquent implements BlogRepository
{
    use FileUploadTrait;

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

    public function paginate($perPage = 10, $search = null, $createdBy = null, $with = null)
    {
        $query = $this->model->query()->active();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($createdBy) {
            $query->where('user_id', $createdBy);
        }

        if ($with) {
            $relations = explode(',', $with);
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadFile($data['photo'], 'blogs');
        }
        return parent::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        if (isset($data['photo'])) {
            $this->deleteFile($record->photo);
            $data['photo'] = $this->uploadFile($data['photo'], 'blogs');
        }
        return parent::update($id, $data);
    }

    public function delete($id)
    {
        $record = $this->find($id);
        $this->deleteFile($record->photo);
        return parent::delete($id);
    }
}
