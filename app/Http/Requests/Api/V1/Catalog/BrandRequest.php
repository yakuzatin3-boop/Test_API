<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ActiveStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [...$this->required(), 'string', 'max:255'],
            'slug' => [...$this->required(), 'string', 'max:255', 'alpha_dash', $this->unique('brands', 'slug')],
            'logo' => [...$this->nullable(), 'string', 'max:500'],
            'description' => [...$this->nullable(), 'string'],
            'status' => ['sometimes', Rule::enum(ActiveStatus::class)],
        ];
    }
}
