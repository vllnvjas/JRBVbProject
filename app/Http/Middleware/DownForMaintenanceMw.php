<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownForMaintenanceMw
{
    /**
     * URI patterns covered by maintenance mode.
     */
    private array $targetUris = [
        'degrees',
        'degrees/*',
        'students/*/edit',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! filter_var(config('app.maintenance.enabled', false), FILTER_VALIDATE_BOOL)) {
            return $next($request);
        }

        if (! $request->is($this->targetUris)) {
            return $next($request);
        }

        return response()
            ->view('maintenance', [
                'title' => 'Down for Maintenance',
                'message' => 'Temporarily unavailable while we apply updates. The rest of the site remains available, and the page will return when maintenance is turned off.',
            ], 503)
            ->header('Retry-After', '3600');
    }
}
