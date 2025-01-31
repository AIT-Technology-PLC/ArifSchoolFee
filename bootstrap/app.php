<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->validateCsrfTokens(['api/*'])
            ->validateCsrfTokens(except: [
                '/arifpay/callback',
                '/telebirr/notify'
            ])
            ->alias([
                'isFeatureAccessible' => \App\Http\Middleware\AllowOnlyEnabledFeatures::class,
                'isEmployeeEnabled' => \App\Http\Middleware\AllowOnlyEnabledUsers::class,
                'isAdmin' => \App\Http\Middleware\AllowOnlyAdminUsers::class,
                'isServiceCenter' => \App\Http\Middleware\AllowOnlyServiceCenterUsers::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $exception, $request) {
            if ($exception->getStatusCode() == 419) {
                return redirect()->route('login');
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return back();
            }
        });
    })->create();
