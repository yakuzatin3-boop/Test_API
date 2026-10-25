<?php

namespace App\Http\Resources\Api\V1\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'quantity' => (int) $this->quantity,
            'price' => (float) $this->price,
            'subtotal' => (float) $this->price * (int) $this->quantity,
            'product' => $this->whenLoaded('product', fn () => $this->product ? [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
            ] : null),
            'variant' => $this->whenLoaded('variant', fn () => $this->variant ? [
                'id' => $this->variant->id,
                'sku' => $this->variant->sku,
                'size' => $this->variant->size,
                'color' => $this->variant->color,
            ] : null),
            'cart' => $this->whenLoaded('cart', fn () => $this->cart ? [
                'id' => $this->cart->id,
                'customer_id' => $this->cart->customer_id,
                'status' => $this->cart->status,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
