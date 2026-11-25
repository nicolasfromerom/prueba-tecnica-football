<?php

namespace App\Modules\Users\Presentation;

use App\Shared\Presentation\ApiRequest;

final class AssignRolesRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['role_ids' => ['present', 'array'], 'role_ids.*' => ['integer', 'distinct', 'exists:roles,id']];
    }
}
