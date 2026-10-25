<?php

namespace App\Http\Resources\Api\V1\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender,
            'user' => $this->whenLoaded('user', fn () => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'role' => $this->user->role,
                'status' => $this->user->status,
            ] : null),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'orders' => $this->whenLoaded('orders', fn () => $this->orders->map(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => (float) $order->total,
                'placed_at' => $order->placed_at?->toIso8601String(),
            ])),
            'addresses_count' => $this->whenCounted('addresses'),
            'orders_count' => $this->whenCounted('orders'),
            'reviews_count' => $this->whenCounted('reviews'),
            'wishlists_count' => $this->whenCounted('wishlists'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
