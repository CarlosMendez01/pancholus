<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, int>  $roles
     */
    public function handle(Request $request, Closure $next, int ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role_id, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
