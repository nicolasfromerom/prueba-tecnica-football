<?php

namespace App\Modules\Roles\Presentation;

use App\Shared\Presentation\ApiRequest;

final class CreateRoleRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:100', 'unique:roles,name']];
    }
}
