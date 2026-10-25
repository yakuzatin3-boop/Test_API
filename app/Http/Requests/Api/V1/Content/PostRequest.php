<?php

namespace App\Http\Requests\Api\V1\Content;

use App\Enums\PostStatus;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => [...$this->required(), 'integer', 'exists:users,id'],
            'category_id' => [...$this->nullable(), 'integer', 'exists:post_categories,id'],
            'title' => [...$this->required(), 'string', 'max:255'],
            'slug' => [...$this->required(), 'string', 'max:255', 'alpha_dash', $this->unique('posts', 'slug')],
            'content' => [...$this->required(), 'string'],
            'thumbnail' => [...$this->nullable(), 'string', 'max:500'],
            'status' => ['sometimes', Rule::enum(PostStatus::class)],
            'published_at' => [...$this->nullable(), 'date'],
            'tags' => [...$this->nullable(), 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
