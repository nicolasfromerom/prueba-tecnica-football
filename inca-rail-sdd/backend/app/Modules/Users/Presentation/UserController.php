<?php

namespace App\Modules\Users\Presentation;

use App\Models\User;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Modules\Roles\Application\ProtectLastAdmin;
use Illuminate\Support\Facades\DB;

final class UserController
{
    public function index(): JsonResponse
    {
        $users = User::with('roles:id,name')->orderBy('id')->get();

        return ApiResponse::success($users);
    }

    public function show(int $id): JsonResponse
    {
        return ApiResponse::success(User::with('roles:id,name')->findOrFail($id));
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return ApiResponse::success($user, 'User created.', status: 201);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        return ApiResponse::success($user->fresh(), 'User updated.');
    }

    public function status(StatusRequest $request, int $id, ProtectLastAdmin $protect): JsonResponse
    {
        $user = DB::transaction(function () use ($request, $id, $protect) {
            $user = User::findOrFail($id);
            $active = $request->boolean('is_active');
            if (!$active) {
                $protect->ensureAnotherActiveAdmin($user);
            }
            $user->is_active = $active;
            $user->save();
            if (!$active) {
                $user->tokens()->update(['revoked' => true]);
            }

            return $user;
        });

        return ApiResponse::success($user, 'User status updated.');
    }
}
