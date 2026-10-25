<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Enums\CartStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => [...$this->required(), 'integer', 'exists:customers,id'],
            'status' => ['sometimes', Rule::enum(CartStatus::class)],
        ];
    }
}
