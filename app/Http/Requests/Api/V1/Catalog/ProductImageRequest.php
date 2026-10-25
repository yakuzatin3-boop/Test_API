<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Http\Requests\Api\V1\ApiFormRequest;

class ProductImageRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [...$this->required(), 'integer', 'exists:products,id'],
            'image_path' => [...$this->required(), 'string', 'max:500'],
            'is_thumbnail' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
