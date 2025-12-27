<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(Throwable $e,$request){
            if($request->is('api/*')){
                if($e instanceof ModelNotFoundException){
                    return response()->json([
                        'status'=>false,
                        'message'=>'Not Found'
                    ], 404);
                }
                return response()->json([
                    'status'=>false,
                    'message'=>'Internal Server Error'
                ], 500);
            }
        });
    })->create();

