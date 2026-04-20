<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // شغّليه فقط على الـ API
        $middleware->group('api', [
            \App\Http\Middleware\LogRequestMiddleware::class,
        ]);

        // alias
        $middleware->alias([
            'freelancer.verified' => \App\Http\Middleware\EnsureFreelancerIsVerified::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
