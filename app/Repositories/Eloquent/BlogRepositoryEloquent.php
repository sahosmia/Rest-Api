<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepository;
use App\Traits\FileUploadTrait;

class BlogRepositoryEloquent extends BaseRepositoryEloquent implements BlogRepository
{
    use FileUploadTrait;

    protected array $with = ['tags', 'user'];

    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    protected function attachTags($blog, array $tags)
    {
        $blog->tags()->attach($tags);
    }

    protected function syncTags($blog, array $tags)
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
        } else {
            $query->with($this->with);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadFile($data['photo'], 'blogs');
        }
        $blog = parent::create($data);

        if (isset($data['tags'])) {
            $this->attachTags($blog, $data['tags']);
        }

        return $blog->load($this->with);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        if (isset($data['photo'])) {
            $this->deleteFile($record->photo);
            $data['photo'] = $this->uploadFile($data['photo'], 'blogs');
        }
        $blog = parent::update($id, $data);

        if (isset($data['tags'])) {
            $this->syncTags($blog, $data['tags']);
        }

        return $blog->load($this->with);
    }

    public function delete($id)
    {
        $record = $this->find($id);
        $this->deleteFile($record->photo);
        return parent::delete($id);
    }

    public function with(array $with)
    {
        $this->with = $with;
        return $this;
    }
}
