<?php

use App\Http\Middleware\AssignRequestId;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', //added this line
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::prefix('transactions')
                ->name('transactions.')
                ->group(base_Path(
                    'routes/transactions.php'
                ));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(AssignRequestId::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
