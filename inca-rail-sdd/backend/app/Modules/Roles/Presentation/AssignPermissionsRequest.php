<?php

namespace App\Modules\Roles\Presentation;

use App\Shared\Presentation\ApiRequest;

final class AssignPermissionsRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['permission_ids' => ['present', 'array'], 'permission_ids.*' => ['integer', 'distinct', 'exists:permissions,id']];
    }
}
