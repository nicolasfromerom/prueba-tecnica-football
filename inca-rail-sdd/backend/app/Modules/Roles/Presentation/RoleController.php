<?php

namespace App\Modules\Roles\Presentation;

use App\Modules\Roles\Infrastructure\Role;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Modules\Permissions\Infrastructure\Permission;
use Illuminate\Support\Facades\DB;

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

    public function assignPermissions(AssignPermissionsRequest $request, int $id): JsonResponse
    {
        $role = DB::transaction(function () use ($request, $id) {
            $role = Role::whereKey($id)->lockForUpdate()->firstOrFail();
            abort_if($role->name === 'admin', 403);
            $permissions = Permission::whereIn('id', $request->validated('permission_ids'))->get();
            foreach ($permissions as $permission) {
                abort_unless($request->user('api')->canPerform($permission->action, $permission->subject), 403);
            }
            $role->permissions()->sync($permissions->pluck('id'));

            return $role->load('permissions');
        });

        return ApiResponse::success($role, 'Permissions assigned.');
    }
}
