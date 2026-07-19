<?php

namespace App\Services;

use App\Exceptions\OrderProductUnavailableException;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected ProductRepositoryInterface $productRepository
    )
    {
    }



    public function getAll(int $perPage = 10): LengthAwarePaginator
    {

       return  $this->orderRepository->getAll($perPage);


    }



    public function getMyOrders(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getByUser($userId, $perPage);
    }




    public function createOrder(int $userId, array $products): Order
    {
        return DB::transaction(function () use ($userId, $products) {
            $attachData = [];
            $errors = [];
            $totalPrice = 0;

            foreach ($products as $productData) {
                $product = $this->productRepository->findById($productData['product_id']);

                if (!$product || $product->status === 'unactive') {
                    $errors[] = [
                        'product_id' => $productData['product_id'],
                        'product_name' => $product?->name ?? 'unknown product',
                        'reason' => $product ? 'Product is inactive' : 'Product not found',
                    ];

                    continue;
                }

                $quantity = (int) $productData['quantity'];
                $price = (float) $product->price;

                $attachData[$product->id] = [
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                ];

                $totalPrice += $price * $quantity;
            }

            if (!empty($errors)) {
                throw new OrderProductUnavailableException($errors);
            }

            $points = max(1, (int) floor($totalPrice / 50));

            /** @var OrderRepositoryInterface $orderRepository */
            $orderRepository = $this->orderRepository;

            $order = $orderRepository->create([
                'order_date' => now()->toDateString(),
                'points' => $points,
                'user_id' => $userId,
                'totalPrice' => $totalPrice,
                'status' => 'pending',
            ]);

            $order->products()->attach($attachData); // pivot table attach

            return $order->load([
                'user:id,name',
                'products.category:id,name',
            ]);
    });
    }
}
