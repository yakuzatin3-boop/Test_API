<?php

namespace App\Http\Resources\Api\V1\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'shipping_fee' => (float) $this->shipping_fee,
            'discount' => (float) $this->discount,
            'subtotal' => (float) $this->subtotal,
            'total' => (float) $this->total,
            'note' => $this->note,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'placed_at' => $this->placed_at?->toIso8601String(),
            'customer' => $this->whenLoaded('customer', fn () => $this->customer ? [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
            ] : null),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'shipments' => ShipmentResource::collection($this->whenLoaded('shipments')),
            'items_count' => $this->whenCounted('items'),
            'payments_count' => $this->whenCounted('payments'),
            'shipments_count' => $this->whenCounted('shipments'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
