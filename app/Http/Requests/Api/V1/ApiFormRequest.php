<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class ApiFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function isUpdate(): bool
    {
        return ! $this->isMethod('POST');
    }

    /**
     * @return list<string>
     */
    protected function required(): array
    {
        return $this->isUpdate() ? ['sometimes', 'required'] : ['required'];
    }

    /**
     * @return list<string>
     */
    protected function nullable(): array
    {
        return ['sometimes', 'nullable'];
    }

    protected function unique(string $table, string $column): Unique
    {
        $rule = Rule::unique($table, $column);

        if ($this->isUpdate() && $this->route('id')) {
            $rule->ignore($this->route('id'));
        }

        return $rule;
    }
}
