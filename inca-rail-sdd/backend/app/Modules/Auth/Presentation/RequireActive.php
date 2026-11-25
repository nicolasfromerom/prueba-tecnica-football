<?php

namespace App\Modules\Auth\Presentation;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequireActive
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user('api')?->is_active, 403);

        return $next($request);
    }
}
