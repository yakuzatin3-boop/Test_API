<?php

namespace App\Http\Requests\Api\V1\Sales;

use App\Enums\PaymentStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [...$this->required(), 'integer', 'exists:orders,id'],
            'payment_method' => [...$this->required(), 'string', 'max:30'],
            'transaction_id' => [...$this->nullable(), 'string', 'max:255', $this->unique('payments', 'transaction_id')],
            'amount' => [...$this->required(), 'numeric', 'min:0'],
            'status' => ['sometimes', Rule::enum(PaymentStatus::class)],
            'paid_at' => [...$this->nullable(), 'date'],
        ];
    }
}
