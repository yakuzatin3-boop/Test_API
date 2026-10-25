<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:30'],
            'role' => ['nullable', 'string', 'max:30'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'user_id' => ['nullable', 'integer'],
            'customer_id' => ['nullable', 'integer'],
            'product_id' => ['nullable', 'integer'],
            'variant_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'integer'],
            'brand_id' => ['nullable', 'integer'],
            'cart_id' => ['nullable', 'integer'],
            'order_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string', 'max:30'],
            'payment_method' => ['nullable', 'string', 'max:30'],
            'carrier' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
