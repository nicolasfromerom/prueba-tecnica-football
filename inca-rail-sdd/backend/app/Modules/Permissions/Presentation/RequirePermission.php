<?php

namespace App\Modules\Permissions\Presentation;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequirePermission
{
    public function handle(Request $request, Closure $next, string $action, string $subject): Response
    {
        abort_unless($request->user('api')?->canPerform($action, $subject), 403);

        return $next($request);
    }
}
