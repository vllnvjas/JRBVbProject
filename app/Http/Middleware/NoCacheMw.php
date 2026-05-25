<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class NoCacheMw
{
    /**
     * Prevent browsers from caching protected pages so the back button
     * cannot show stale authenticated content after logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            /** @var \Symfony\Component\HttpFoundation\Response $response */
            $response = $next($request);
        } catch (Throwable $e) {
            Log::error('Request failed in NoCacheMw: '.$e->getMessage());

            if (View::exists('errors.503')) {
                $response = response()->view('errors.503', ['message' => $e->getMessage()], 503);
            } else {
                $response = new Response('Service Unavailable', 503);
            }
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
}
