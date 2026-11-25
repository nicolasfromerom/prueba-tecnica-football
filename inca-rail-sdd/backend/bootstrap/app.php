<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Shared\Presentation\ApiResponse;
use App\Shared\Presentation\ForceJson;
use App\Modules\Auth\Presentation\RequireActive;
use App\Modules\Permissions\Presentation\RequirePermission;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(ForceJson::class);
        $middleware->alias(['active' => RequireActive::class, 'permission' => RequirePermission::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->expectsJson() || !$request->is('up'),
        );
        $exceptions->render(function (\Throwable $error, Request $request) {
            if ($request->is('up')) {
                return null;
            }

            $status = match (true) {
                $error instanceof ValidationException => 422,
                $error instanceof AuthenticationException => 401,
                $error instanceof ModelNotFoundException => 404,
                $error instanceof HttpExceptionInterface => $error->getStatusCode(),
                default => 500,
            };
            $code = match ($status) {
                401 => 'UNAUTHENTICATED',
                403 => 'FORBIDDEN',
                404 => 'NOT_FOUND',
                422 => 'VALIDATION_ERROR',
                429 => 'RATE_LIMITED',
                502 => 'UPSTREAM_ERROR',
                503 => 'SERVICE_UNAVAILABLE',
                default => 'SERVER_ERROR',
            };
            $message = match ($status) {
                401 => 'Unauthenticated.',
                403 => 'Forbidden.',
                404 => 'Not found.',
                422 => 'Validation failed.',
                429 => 'Rate limit exceeded.',
                502 => 'Upstream error.',
                503 => 'Service unavailable.',
                default => 'Server error.',
            };
            $details = $error instanceof ValidationException ? $error->errors() : [];

            return ApiResponse::error($code, $message, $status, $details);
        });
    })->create();
