<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class ProductController2 extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ProductService $productService) {}



    public function index(): JsonResponse
    {
        $products = $this->productService->getAll();

        return $this->successResponse($products, 'All products retrieved successfully.', 200);
    }




    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->findById($id);

            return $this->successResponse($product, 'Product retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), $e->getCode() ?: 404);
        }
    }



    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->create($request->validated());

            $responseData = [
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'category_name' => optional($product->category)->name,
            ];

            return $this->createdResponse($responseData, 'Product stored successfully.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return $this->errorResponse(null, "Cannot store {$request->name}; this product already exists.", 409);
            }

            return $this->errorResponse($e->getMessage(), 'Database error, please try again later.', 500);
        }
    }



    public function changeStatus(int $id): JsonResponse
    {
        try {
            $product = $this->productService->toggleStatus($id);

            return $this->successResponse([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'product_price' => $product->price,
                'product_status' => $product->status,
                'category_id' => optional($product->category)->id,
                'category_name' => optional($product->category)->name,
            ], 'Product status changed successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), $e->getCode() ?: 404);
        }
    }





    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $oldProduct = $this->productService->findById($id);
            $updatedProduct = $this->productService->update($id, $request->validated());

            return $this->successResponse([
                'oldData' => $oldProduct,
                'newData' => $updatedProduct,
            ], 'Product updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), $e->getCode() ?: 404);
        }
    }




    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productService->delete($id);

            return $this->successResponse(['deleted_id' => $id], 'Product deleted successfully.', 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();

            // common cause: foreign key constraint preventing delete
            if (strpos($message, 'Integrity constraint') !== false || strpos($message, 'foreign key') !== false) {
                return $this->errorResponse(null, 'Cannot delete product because related records exist.', 409);
            }

            return $this->errorResponse(null, $message, $e->getCode() ?: 404);
        }
    }





    //for users

    public function listActiveProducts(): JsonResponse
    {
        $products = $this->productService->listActiveProducts();

        if ($products->isEmpty()) {
            return $this->errorResponse(null, 'No active products found.', 404);
        }

        return $this->successResponse($products, 'Active products retrieved successfully.', 200);
    }




    public function searchProductByName(SearchProductRequest $request): JsonResponse
    {
        $products = $this->productService->searchByName($request->name);

        if ($products->isEmpty()) {
            return $this->errorResponse(null, 'No active products found matching the search criteria.', 404);
        }

        return $this->successResponse($products, 'Search results retrieved successfully.', 200);
    }

}
