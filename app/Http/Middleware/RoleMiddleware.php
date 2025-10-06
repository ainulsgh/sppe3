<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) abort(403);
        if (!in_array($user->role, $roles)) abort(403, 'Tidak memiliki akses.');
        return $next($request);
    }
}
