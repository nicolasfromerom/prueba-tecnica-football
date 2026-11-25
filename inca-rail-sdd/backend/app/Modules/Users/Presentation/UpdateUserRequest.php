<?php

namespace App\Modules\Users\Presentation;

use App\Shared\Presentation\ApiRequest;
use Illuminate\Validation\Rule;

final class UpdateUserRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('id'))],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ];
    }
}
