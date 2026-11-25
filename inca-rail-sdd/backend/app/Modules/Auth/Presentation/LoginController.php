<?php

namespace App\Modules\Auth\Presentation;

use App\Modules\Auth\Application\Login;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

final class LoginController
{
    public function __invoke(LoginRequest $request, Login $login): JsonResponse
    {
        $result = $login->execute($request->string('email')->toString(), $request->string('password')->toString());

        return $result
            ? ApiResponse::success($result, 'Authenticated.')
            : ApiResponse::error('INVALID_CREDENTIALS', 'Invalid credentials.', 401);
    }
}
