<?php

namespace App\Modules\Auth\Presentation;

use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user('api')->load('roles.permissions');
        $rules = $user->roles->flatMap(fn ($role) => $role->permissions->map(fn ($permission) => [
            'action' => $permission->action,
            'subject' => $permission->subject,
        ]))->unique(fn ($rule) => $rule['action'].':'.$rule['subject'])->values();

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'roles' => $user->roles->pluck('name'),
            'rules' => $rules,
        ]);
    }
}
