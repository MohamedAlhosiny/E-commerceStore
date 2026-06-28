<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(protected Order $model)
    {
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with([
                'user:id,name',
                'products' => function ($query) {
                    $query->select('products.id', 'products.name', 'products.description', 'products.price', 'products.category_id');
                },
                'products.category:id,name',
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->with([
                'user:id,name',
                'products' => function ($query) {
                    $query->select('products.id', 'products.name', 'products.description', 'products.price', 'products.category_id');
                },
                'products.category:id,name',
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int|string $id): ?Order
    {
        return $this->model->find($id);
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }
}
