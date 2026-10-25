<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Http\Requests\Api\V1\ApiFormRequest;

class OrderItemRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [...$this->required(), 'integer', 'exists:orders,id'],
            'product_id' => [...$this->nullable(), 'integer', 'exists:products,id'],
            'variant_id' => [...$this->nullable(), 'integer', 'exists:product_variants,id'],
            'product_name' => [...$this->nullable(), 'string', 'max:255'],
            'sku' => [...$this->nullable(), 'string', 'max:100'],
            'quantity' => [...$this->required(), 'integer', 'min:1', 'max:1000'],
            'price' => [...$this->required(), 'numeric', 'min:0'],
        ];
    }
}
