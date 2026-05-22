<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        //global middleware
        $middleware->append(\App\Http\Middleware\PromotionMw::class);
        $middleware->append(\App\Http\Middleware\DownForMaintenanceMw::class);
        $middleware->append(\App\Http\Middleware\NoCacheMw::class);

        //middlewaregroup
        $middleware->group('group_middleware', [
            \App\Http\Middleware\MiddlewareOne::class,
            \App\Http\Middleware\MiddlewareTwo::class,
            \App\Http\Middleware\DownForMaintenanceMw::class,
            
        ]);
        //route middleware
        $middleware->alias([
            'maintenance' => \App\Http\Middleware\DownForMaintenanceMw::class,
            'promotion' => \App\Http\Middleware\PromotionMw::class,
            'session.auth' => \App\Http\Middleware\AuthenticateSessionMw::class,
            'role.admin' => \App\Http\Middleware\CheckAdminRole::class,
            'role.teacher' => \App\Http\Middleware\CheckTeacherRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
