<?php

namespace App\Http\Requests\Api\V1\Access;

use App\Enums\ActiveStatus;
use App\Enums\UserRole;
use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [...$this->required(), 'string', 'max:255'],
            'email' => [...$this->required(), 'string', 'email', 'max:255', $this->unique('users', 'email')],
            'password' => $this->isUpdate()
                ? ['sometimes', 'nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'],
            'role' => ['sometimes', Rule::enum(UserRole::class)],
            'status' => ['sometimes', Rule::enum(ActiveStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
