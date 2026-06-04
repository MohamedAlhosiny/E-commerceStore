<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): void;
}
