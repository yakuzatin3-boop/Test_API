<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProductRequest extends FormRequest
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
        $maxPriceRules = ['nullable', 'numeric', 'min:0'];

        if ($this->filled('min_price')) {
            $maxPriceRules[] = 'gte:min_price';
        }

        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'status' => ['nullable', Rule::enum(ProductStatus::class)],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => $maxPriceRules,
            'sort' => ['nullable', 'string', Rule::in(['latest', 'oldest', 'price_asc', 'price_desc', 'name_asc', 'name_desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
