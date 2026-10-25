<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Enums\ActiveStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [...$this->required(), 'string', 'max:255'],
            'slug' => [...$this->required(), 'string', 'max:255', 'alpha_dash', $this->unique('categories', 'slug')],
            'description' => [...$this->nullable(), 'string'],
            'parent_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'status' => ['sometimes', Rule::enum(ActiveStatus::class)],
        ];
    }
}
