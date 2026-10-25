<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ReviewStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $productRules = [...$this->required(), 'integer', 'exists:products,id'];

        if ($this->filled('customer_id') && $this->filled('product_id')) {
            $unique = Rule::unique('reviews', 'product_id')
                ->where('customer_id', $this->input('customer_id'));

            if ($this->isUpdate() && $this->route('id')) {
                $unique->ignore($this->route('id'));
            }

            $productRules[] = $unique;
        }

        return [
            'product_id' => $productRules,
            'customer_id' => [...$this->required(), 'integer', 'exists:customers,id'],
            'rating' => [...$this->required(), 'integer', 'between:1,5'],
            'comment' => [...$this->nullable(), 'string'],
            'status' => ['sometimes', Rule::enum(ReviewStatus::class)],
        ];
    }
}
