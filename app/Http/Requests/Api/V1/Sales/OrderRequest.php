<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => [...$this->required(), 'integer', 'exists:customers,id'],
            'order_number' => [...$this->nullable(), 'string', 'max:50', $this->unique('orders', 'order_number')],
            'status' => ['sometimes', Rule::enum(OrderStatus::class)],
            'shipping_fee' => ['sometimes', 'numeric', 'min:0'],
            'discount' => ['sometimes', 'numeric', 'min:0'],
            'note' => [...$this->nullable(), 'string', 'max:2000'],
            'shipping_address' => [...$this->nullable(), 'array'],
            'billing_address' => [...$this->nullable(), 'array'],
            'placed_at' => [...$this->nullable(), 'date'],
        ];
    }
}
