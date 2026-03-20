<?php

namespace App\Http\Middleware;

use App\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Log every state-changing request (POST/PUT/PATCH/DELETE) for authenticated users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AuditLogService::log(
                'http.' . strtolower($request->method()),
                null,
                [],
                [],
                $request->route()?->getName() ?? $request->path()
            );
        }

        return $response;
    }
}
