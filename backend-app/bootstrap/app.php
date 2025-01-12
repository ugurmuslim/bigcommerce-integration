<?php

use App\Http\Middleware\ExceptJson;
use App\Http\Middleware\ResponseWrapper;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([ExceptJson::class]);
        $middleware->api([ResponseWrapper::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
