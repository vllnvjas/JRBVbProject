<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSessionMw
{
    /**
     * Redirect guests away from protected pages after session logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('authenticated_user_id')) {
            return redirect()->route('login')->with('msg', 'Please log in first.');
        }

        return $next($request);
    }
}
