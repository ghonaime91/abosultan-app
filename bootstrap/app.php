<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // then: function() {
        //     Route::middleware('api')
        //         ->name('auth')
        //         ->group(base_path('routes/AuthApi.php'));
        // }
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        # Redirect guests to login page
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*')) 
                return route('login');
        });

        # Setting App Language Middleware
        $middleware->alias([
            'appLanguage' => \App\Http\Middleware\SetAppLanguage::class,            
        ]);

        $middleware->priority([
            \App\Http\Middleware\SetAppLanguage::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        

        # Handle 404 not found exception
        $exceptions->render(function (NotFoundHttpException $e, Request $request)
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.not_found')
                ], 404);
            }             
        });

        # Handle 405 method not allowed exception
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request)
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.method_not_allowed')
                ], 405);
            }             
        });

        # Handle authentication exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.unauthenticated')
                ], 401);
            }
        });

        # Handle InvalidSignatureException
        $exceptions->render(function (InvalidSignatureException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.verification_link_invalid')
                ], 400);
            }
        });


        # Handle No model found exception
        $exceptions->render(function (ModelNotFoundException  $e, Request $request)
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.not_found')
                ], 404);
            }             
        });

        # Handle too many requestes
        $exceptions->render(function (ThrottleRequestsException  $e, Request $request)
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.throttle')
                ], 429);
            }             
        });

        # Handle general exceptions
        // $exceptions->render(function (Throwable $e, Request $request) {
        //     if ($request->wantsJson()) {
        //         return response()->json([
        //             'error' => true,
        //             'message' => __('exceptions.internal_server_error')
        //         ], 500);
        //     }

        // });    

    })->create();
