<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Http\Requests\Api\V1\ApiFormRequest;

class InventoryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [...$this->required(), 'integer', 'exists:products,id'],
            'variant_id' => ['sometimes', 'nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => [...$this->required(), 'integer'],
            'warehouse_location' => [...$this->nullable(), 'string', 'max:255'],
        ];
    }
}
