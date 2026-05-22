<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromotionMw
{
    /**
     * URI patterns where the promotion should appear.
     *
     * Example patterns:
     * - students
     * - students/*
     * - about
     */
    private array $targetUris = [
        'students',
        'students/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is($this->targetUris)) {
            view()->share('middlewareBanner', [
                'type' => 'promotion',
                'badge' => 'Limited Time',
                'title' => 'Student Enrollment Promo',
                'description' => 'Get 50% off on enrollment fees this week.',
                'code' => 'PROMO50',
                'expires_at' => 'April 30, 2026',
            ]);
        }

        return $next($request);
    }
}
