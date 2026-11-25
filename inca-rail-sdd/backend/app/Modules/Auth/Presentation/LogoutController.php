<?php

namespace App\Modules\Auth\Presentation;

use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user('api')->token()->revoke();

        return ApiResponse::success(null, 'Logged out.');
    }
}
