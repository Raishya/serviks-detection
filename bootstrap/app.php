<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreventBackHistory;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            PreventBackHistory::class,
        ]);
        $middleware->alias([
            'preventBackHistory' => \App\Http\Middleware\PreventBackHistory::class,
            'user-access' => \App\Http\Middleware\UserAccess::class,
            'log.session' => \App\Http\Middleware\LogSessionData::class,
            'check.user.access' => \App\Http\Middleware\CheckUserAccess::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
