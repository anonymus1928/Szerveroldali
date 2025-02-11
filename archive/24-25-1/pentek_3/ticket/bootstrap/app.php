<?php

use App\Http\Middleware\ForceJSONResponseAPI;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [ ForceJSONResponseAPI::class ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if($request->is('api/*')) {
                return response()->json([
                    'error' => 'A kért erőforrás nem található.',
                ], 404);
            }
        });

        // $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
        //     if($request->is('api/*')) {
        //         return response()->json([
        //             'error' => 'NONO.',
        //         ], 403);
        //     }
        // });
    })->create();
