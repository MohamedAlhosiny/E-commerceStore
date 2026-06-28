<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderStatusServiceInterface;
use App\Models\Order;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderStatusService implements OrderStatusServiceInterface
{
    private const STATUSES = [
        'pending',
        'processing',
        'completed',
        'cancelled',
    ];

    private const VALID_TRANSITIONS = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function __construct(protected OrderRepositoryInterface $orderRepository)
    {
    }

    public function changeStatus(int|string $orderId, string $newStatus): Order
    {
        if (!in_array($newStatus, self::STATUSES, true)) {
            throw new DomainException('Invalid order status value.');
        }

        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new ModelNotFoundException('Order not found.');
        }

        $currentStatus = $order->status;
        $allowedStatuses = self::VALID_TRANSITIONS[$currentStatus] ?? []; // هل مسموح بهذا الانتقال من الحالة الحالية إلى الحالة الجديدة

        if (!in_array($newStatus, $allowedStatuses, true)) {
            throw new DomainException("Invalid status transition from {$currentStatus} to {$newStatus}.");
        }

        $order->status = $newStatus;
        $order->save();

        return $order->load([
            'user:id,name',
            'products' => function ($query) {
                $query->select('products.id', 'products.name', 'products.price', 'products.category_id');
            },
            'products.category:id,name',
        ]);
    }
}
