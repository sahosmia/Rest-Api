<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(protected CategoryRepository $categoryRepository)
    {
        //
    }

    public function index(Request $request)
    {
        try {
            $categories = $this->categoryRepository->paginate(
                $request->query('per_page', 10),
                $request->query('search'),
                $request->query('with')
            );
            return new CategoryCollection($categories);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve categories.');
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryRepository->create($request->validated());
            return $this->successResponse(new CategoryResource($category), 'Category created successfully.', 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to create category.');
        }
    }

    public function show(Category $category)
    {
        try {
            return $this->successResponse(new CategoryResource($category), 'Category retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve category.');
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $updatedCategory = $this->categoryRepository->update($category->id, $request->validated());
            return $this->successResponse(new CategoryResource($updatedCategory), 'Category updated successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to update category.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryRepository->delete($category->id);
            return $this->successResponse(null, 'Category deleted successfully.', 204);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to delete category.');
        }
    }

    public function list()
    {
        try {
            $data = $this->categoryRepository->allForList();
            return $this->successResponse($data, 'Categories list retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve categories list.');
        }
    }
}
