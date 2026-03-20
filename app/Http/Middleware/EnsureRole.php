<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,job_poster')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            $normalizedRole = strtolower(trim($role));

            if ($normalizedRole === '') {
                continue;
            }

            // Support both Spatie role assignments and the users.role column.
            if ($user->hasRole($normalizedRole) || strtolower((string) $user->role) === $normalizedRole) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
