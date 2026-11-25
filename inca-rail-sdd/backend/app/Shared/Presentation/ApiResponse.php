<?php

namespace App\Shared\Presentation;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(mixed $data, string $message = 'OK', array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data, 'meta' => (object) $meta], $status);
    }

    public static function error(string $code, string $message, int $status, array $details = []): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message, 'data' => null, 'meta' => (object) [], 'error' => ['code' => $code, 'details' => (object) $details]], $status);
    }
}
