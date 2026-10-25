<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['sometimes', 'nullable', 'integer', 'exists:brands,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->route('product')),
            ],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'decimal:0,2'],
            'original_price' => ['sometimes', 'nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::enum(ProductStatus::class)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_bestseller' => ['sometimes', 'boolean'],
            'is_flash_sale' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
