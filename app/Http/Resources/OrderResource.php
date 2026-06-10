<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_date' => $this->order_date,
            'status' => $this->status,
            'points' => $this->points,
            'total_price' => $this->totalPrice,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'quantity' => $product->pivot->quantity,
                        'pivot_price' => $product->pivot->price,
                        'product_name' => $product->pivot->product_name,
                        'category' => [
                            'id' => $product->category?->id,
                            'name' => $product->category?->name,
                        ],
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

