<?php
namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{

    public function __construct(protected CategoryRepositoryInterface $categoryRepository){}


   public function getAll(): LengthAwarePaginator
    {
        return $this->categoryRepository->getAll();
    }

    public function findById(int $id) :  Category
    {
        $showCategory = $this->categoryRepository->findById($id);
        // dd($showCategory);

        if (!$showCategory) {
            throw new \Exception(' Category not found', 404);
        }

        return $showCategory;
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->findById($id);
        // dd($category);

        if (!$category) {
            throw new \Exception(' Category not found', 404);
        }
        return $this->categoryRepository->update($category, $data);
    }

    public function delete(int $id)
    {
        // dd($id);
        $category = $this->findById($id);
        // dd($category);

         $this->categoryRepository->delete($category);

    }


}
