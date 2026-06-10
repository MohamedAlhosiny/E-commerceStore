<?php

namespace App\Services;

use App\Interfaces\OrderServiceInterface;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService implements OrderServiceInterface
{
    public function __construct(protected OrderRepositoryInterface $orderRepository)
    {
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getAll($perPage);
    }

    public function getMyOrders(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getByUser($userId, $perPage);
    }
}