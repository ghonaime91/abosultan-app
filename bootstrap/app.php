<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        # Redirect guests to login page
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*')) 
                return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {

        # Handle 404 not found exception
        $exceptions->render(function (NotFoundHttpException $e, Request $request)
        {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'المورد غير موجود'
                ], 404);
            }             
        });

        # Handle 405 method not allowed exception
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request)
        {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'خطأ في الطلب'
                ], 405);
            }             
        });

        # Handle authentication exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            }
        });

        # Handle general exceptions
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'خطأ في الخادم'
                ], 500);
            }
        });
    

    })->create();
