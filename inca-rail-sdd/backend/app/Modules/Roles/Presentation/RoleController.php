<?php

namespace App\Modules\Roles\Presentation;

use App\Modules\Roles\Infrastructure\Role;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

final class RoleController
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Role::with('permissions:id,action,subject')->orderBy('id')->get());
    }

    public function show(int $id): JsonResponse
    {
        return ApiResponse::success(Role::with('permissions:id,action,subject')->findOrFail($id));
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        return ApiResponse::success(Role::create($request->validated()), 'Role created.', status: 201);
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        abort_if(in_array($role->name, ['admin', 'consulta'], true) && $request->validated('name') !== $role->name, 403);
        $role->update($request->validated());

        return ApiResponse::success($role, 'Role updated.');
    }
}
