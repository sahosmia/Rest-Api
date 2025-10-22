<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepository;
use App\Traits\ApiStatus;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiStatus;

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $per_page = $request->query('per_page', 10);

            $query = Category::query();

            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $datas = $query->paginate($per_page);

            if ($datas->isEmpty()) {
                return $this->successResponse([], 'No categories found');
            }
            return $this->successResponse($datas, 'Categories fetched successfully');
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = Category::create($request->validated());
            return response()->json(['data' => $data, 'message' => 'Data Insert Successfully'], 201);
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function show(Category $category)
    {
        try {
            return response()->json(['data' => $category, 'message' => "Data Show Successfully"], 200);
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return response()->json(['data' => $category, 'message' => 'Data Update Successfully'], 200);
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['data' => $category, 'message' => 'Data Delete Successfully'], 200);
        } catch (\Exception $execption) {
            return $this->StatusError($execption->getMessage());
        }
    }
}
