<?php

namespace App\Http\Requests\Api\V1\Customers;

use App\Http\Requests\Api\V1\ApiFormRequest;

class CustomerRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id', $this->unique('customers', 'user_id')],
            'name' => [...$this->required(), 'string', 'max:255'],
            'email' => [...$this->required(), 'string', 'email', 'max:255', $this->unique('customers', 'email')],
            'phone' => [...$this->nullable(), 'string', 'max:30'],
            'date_of_birth' => [...$this->nullable(), 'date', 'before:today'],
            'gender' => [...$this->nullable(), 'string', 'in:male,female,other'],
        ];
    }
}
