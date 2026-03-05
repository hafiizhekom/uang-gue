<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->api(append: [
            \App\Http\Middleware\ApiTrafficLogger::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }
        });
        
        //
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e, Request $request) {
        if ($request->is('api/*')) {
            Log::warning('API Error Access:', [
                'status'  => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'url'     => $request->fullUrl(),
                'user_id' => auth()->id() ?? 'Guest',
                'ip'      => $request->ip(),
            ]);

            $message = $e->getStatusCode() === 404 
            ? 'The requested resource was not found.'
            : ($e->getMessage() ?: 'Something went wrong.');
            
            return response()->json([
                'status'  => 'Error',
                'message' => $message,
            ], $e->getStatusCode());
        }
    });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'Fail',
                    'message' => 'The given data was invalid.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
    })->create();
