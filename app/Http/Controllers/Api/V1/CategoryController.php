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

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(protected CategoryRepository $categoryRepository)
    {
        //
    }

    public function index(Request $request)
    {
        $categories = $this->categoryRepository->paginate(
            $request->query('per_page', 10),
            $request->query('search'),
            $request->query('with')
        );
        return new CategoryCollection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryRepository->create($request->validated());
        return $this->successResponse(new CategoryResource($category), 'Category created successfully.', 201);
    }

    public function show(Category $category)
    {
        return $this->successResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryRepository->update($category->id, $request->validated());
        return $this->successResponse(new CategoryResource($updatedCategory), 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category->id);
        return response()->noContent();
    }

    public function list()
    {
        $data = $this->categoryRepository->allForList();
        return $this->successResponse($data, 'Categories list retrieved successfully.');
    }
}
