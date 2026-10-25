<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ActiveStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [...$this->required(), 'integer', 'exists:products,id'],
            'size' => [...$this->nullable(), 'string', 'max:50'],
            'color' => [...$this->nullable(), 'string', 'max:50'],
            'sku' => [...$this->required(), 'string', 'max:100', $this->unique('product_variants', 'sku')],
            'price' => [...$this->nullable(), 'numeric', 'min:0', 'decimal:0,2'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::enum(ActiveStatus::class)],
        ];
    }
}
