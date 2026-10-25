<?php

namespace App\Http\Requests\Api\V1\Customers;

use App\Http\Requests\Api\V1\ApiFormRequest;

class AddressRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => [...$this->required(), 'integer', 'exists:customers,id'],
            'type' => ['sometimes', 'string', 'in:shipping,billing'],
            'address_line1' => [...$this->required(), 'string', 'max:255'],
            'address_line2' => [...$this->nullable(), 'string', 'max:255'],
            'city' => [...$this->required(), 'string', 'max:100'],
            'state' => [...$this->nullable(), 'string', 'max:100'],
            'postal_code' => [...$this->nullable(), 'string', 'max:20'],
            'country' => [...$this->required(), 'string', 'max:100'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}
