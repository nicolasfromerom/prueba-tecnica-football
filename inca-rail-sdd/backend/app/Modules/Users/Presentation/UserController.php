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
}
