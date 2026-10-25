<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Http\Requests\Api\V1\ApiFormRequest;

class CartItemRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => [...$this->required(), 'integer', 'exists:carts,id'],
            'product_id' => [...$this->required(), 'integer', 'exists:products,id'],
            'variant_id' => [...$this->nullable(), 'integer', 'exists:product_variants,id'],
            'quantity' => [...$this->required(), 'integer', 'min:1', 'max:1000'],
            'price' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
