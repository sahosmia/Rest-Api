<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepository;
use App\Traits\ApiStatus;

class CategoryController extends Controller
{
    use ApiStatus;

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        try {
            $datas = $this->categoryRepository->all();
            return CategoryResource::collection($datas)->additional($this->StatusResource());
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryRepository->create($request->validated());
            return (new CategoryResource($category))->additional($this->StatusSuccess([], 'Data Store Successfuly'));
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function show(Category $category)
    {
        try {
            return (new CategoryResource($category))->additional($this->StatusResource());
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $updatedCategory = $this->categoryRepository->update($category->id, $request->validated());
            return $this->StatusSuccess($updatedCategory, 'Data Update Successfuly');
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryRepository->delete($category->id);
            return $this->StatusSuccess([], 'Data Delete Successfuly');
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }
}