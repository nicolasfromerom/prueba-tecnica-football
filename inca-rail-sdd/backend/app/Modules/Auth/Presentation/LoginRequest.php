<?php

namespace App\Modules\Auth\Presentation;

use App\Shared\Presentation\ApiRequest;

final class LoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['email' => ['required', 'email'], 'password' => ['required', 'string']];
    }
}
