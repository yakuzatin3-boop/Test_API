<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class WishlistRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $productRules = [...$this->required(), 'integer', 'exists:products,id'];

        if ($this->filled('customer_id') && $this->filled('product_id')) {
            $unique = Rule::unique('wishlists', 'product_id')
                ->where('customer_id', $this->input('customer_id'));

            if ($this->isUpdate() && $this->route('id')) {
                $unique->ignore($this->route('id'));
            }

            $productRules[] = $unique;
        }

        return [
            'product_id' => $productRules,
            'customer_id' => [...$this->required(), 'integer', 'exists:customers,id'],
        ];
    }
}
