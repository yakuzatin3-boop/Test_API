<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Enums\ShipmentStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class ShipmentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [...$this->required(), 'integer', 'exists:orders,id'],
            'tracking_number' => [...$this->nullable(), 'string', 'max:100'],
            'carrier' => [...$this->nullable(), 'string', 'max:100'],
            'shipping_address' => [...$this->nullable(), 'array'],
            'status' => ['sometimes', Rule::enum(ShipmentStatus::class)],
            'shipped_at' => [...$this->nullable(), 'date'],
            'delivered_at' => [...$this->nullable(), 'date', 'after_or_equal:shipped_at'],
        ];
    }
}
