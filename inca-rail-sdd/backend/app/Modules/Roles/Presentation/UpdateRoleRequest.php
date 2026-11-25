<?php

namespace App\Modules\Roles\Presentation;

use App\Shared\Presentation\ApiRequest;
use Illuminate\Validation\Rule;

final class UpdateRoleRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($this->route('id'))]];
    }
}
