<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepository) {}

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->productRepository->getAll($perPage);
    }

    public function findById(int $id): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found', 404);
        }

        return $product;
    }

    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->findById($id);

        return $this->productRepository->update($product, $data);
    }

    public function delete(int $id): void
    {
        $product = $this->findById($id);

        $this->productRepository->delete($product);
    }

    public function toggleStatus(int $id): Product
    {
        $product = $this->findById($id);

        return $this->productRepository->toggleStatus($product);
    }

    public function listActiveProducts(): Collection
    {
        return $this->productRepository->listActiveProducts();
    }

    public function searchByName(string $name): Collection
    {
        return $this->productRepository->searchActiveByName($name);
    }
}
