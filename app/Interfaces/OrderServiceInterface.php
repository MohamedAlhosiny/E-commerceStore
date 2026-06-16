<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;

    public function getMyOrders(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function createOrder(int $userId, array $products): Order;
}
