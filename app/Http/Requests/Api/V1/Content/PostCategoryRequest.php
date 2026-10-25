<?php

namespace App\Http\Requests\Api\V1\Content;

use App\Http\Requests\Api\V1\ApiFormRequest;

class PostCategoryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [...$this->required(), 'string', 'max:255'],
            'slug' => [...$this->required(), 'string', 'max:255', 'alpha_dash', $this->unique('post_categories', 'slug')],
            'description' => [...$this->nullable(), 'string'],
        ];
    }
}
