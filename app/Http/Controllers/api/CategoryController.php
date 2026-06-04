<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        return $this->successResponse($categories, 'All categories retrieved successfully.', 200);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return $this->createdResponse($category->name, 'Category created successfully.');
    }

    public function show(int $id)
    {
        $category  = $this->categoryService->findById($id);
        return $this->successResponse($category, 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        // dd($id);
        // dd($category);
        // dd($request->validated()); // to check PUT method you should test data in raw form in postman and not in form-data

        try {
            $updated = $this->categoryService->update($id, $request->validated());

            return $this->successResponse($updated, 'Category updated successfully.');
        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 'Failed to update category.', $e->getCode());
        }
    }


    public function destroy(int $id): JsonResponse

    {
        try {

            $this->categoryService->delete($id);

            return $this->successResponse(
                null,
                'Category deleted successfully.'
            );
        } catch (\Exception $e) {

            return $this->notFoundResponse(
                $e->getMessage()
            );
        }
    }
}
