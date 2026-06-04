<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(protected Category $model) {}

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function findById(int $id): ?Category
    {
        return $this->model->find($id);
    }

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        // dd($data);
        $category->update($data);
        return $category -> fresh();
    }

    public function delete( Category $category): void
    {
        // dd($id);

        $category->delete();
    }
}
