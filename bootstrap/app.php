<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Append visitor tracking and enterprise security headers to all web requests
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\TrackVisitorMiddleware::class,
        ]);

        // Register custom middleware aliases
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'role'       => \App\Http\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);

        // Exclude payment gateway webhook and callback POST routes from CSRF
        $middleware->validateCsrfTokens(except: [
            'payment/phonepe/*',
            'payment/phonepe/callback',
            'payment/razorpay/*',
            'api/checkout/address/save',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();