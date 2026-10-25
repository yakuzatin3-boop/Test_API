<?php

namespace App\Http\Requests\Api\V1\Catalog;

use App\Http\Requests\Api\V1\ApiFormRequest;

class TagRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [...$this->required(), 'string', 'max:100'],
            'slug' => [...$this->required(), 'string', 'max:100', 'alpha_dash', $this->unique('tags', 'slug')],
        ];
    }
}
