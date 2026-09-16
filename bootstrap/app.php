<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('bank:poll-pending')->everyFifteenSeconds()->withoutOverlapping(2);
        $schedule->command('bank:reconcile-overnight')->dailyAt('02:30');
    })
    ->withEvents(false)
    ->withMiddleware(function (Middleware $middleware): void {
        //
        // Register custom middleware aliases
        $middleware->alias([
            'bot.detect' => \App\Http\Middleware\DetectBot::class,
            'cors' => \App\Http\Middleware\CorsMiddleware::class,
        ]);
        
        // Add CORS middleware to all API routes
        $middleware->api(prepend: [
            \App\Http\Middleware\CorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
