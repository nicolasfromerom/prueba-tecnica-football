<?php

namespace App\Modules\Users\Presentation;

use App\Models\User;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

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
}
