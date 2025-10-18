<?php

use App\Http\Middleware\XSS;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SetLanguageFront;
use App\Http\Middleware\StoreUserLanguage;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn() => route('filament.admin.auth.login'));
        $middleware->validateCsrfTokens(
            except: ['client/razorpay-payment-success']
        );
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'storeLanguage' => StoreUserLanguage::class,
            'setLanguageFront' => SetLanguageFront::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
