<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function create(array $data): Order;
}