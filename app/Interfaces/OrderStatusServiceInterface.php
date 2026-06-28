<?php

namespace App\Interfaces;

use App\Models\Order;

interface OrderStatusServiceInterface
{
    public function changeStatus(int|string $orderId, string $newStatus): Order;
}
