<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model) {}

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('category')->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with('category')->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data)->load('category');
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh()->load('category');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function toggleStatus(Product $product): Product
    {
        $product->status = $product->status === 'unactive' ? 'active' : 'unactive';
        $product->save();

        return $product->fresh()->load('category');
    }

    public function listActiveProducts(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->with(['category' => function ($query) {
                $query->select('id', 'name');
            }])
            ->select('id', 'name', 'description', 'price', 'status', 'category_id')
            ->get();
    }

    public function searchActiveByName(string $name): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->where('name', 'LIKE', "%{$name}%")
            ->with(['category' => function ($query) {
                $query->select('id', 'name');
            }])
            ->select('id', 'name', 'description', 'price', 'status', 'category_id')
            ->get();
    }
}
