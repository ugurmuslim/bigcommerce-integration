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
        $middleware->append([ExceptJson::class]);
        $middleware->api([ResponseWrapper::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function ($response) {
            if ($response instanceof JsonResponse) {
                if ($response->getStatusCode() === 500) {
                    return response()->json([
                        'message' => 'An error occurred, please try again later.',
                    ], 500);
                }
            } else {
                if ($response->getStatusCode() === 500) {
                    return response()->json([
                        'message' => 'An error occurred, please try again later.',
                    ], 500);
                }
            }


            return $response;
        });
    })->create();
