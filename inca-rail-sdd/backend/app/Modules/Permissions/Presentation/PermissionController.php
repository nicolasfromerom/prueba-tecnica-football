<?php

namespace App\Modules\Permissions\Presentation;

use App\Modules\Permissions\Infrastructure\Permission;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

final class PermissionController
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Permission::orderBy('subject')->orderBy('action')->get());
    }
}
