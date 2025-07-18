<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SetSimulasiTanggalMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Middleware simulasi tanggal
        $middleware->appendToGroup('web', [
            SetSimulasiTanggalMiddleware::class,
        ]);

       
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kosongin aja kalo belum dipake
    })
    ->booting(function ($app) {
        $app->booted(function () use ($app) {
            $schedule = $app->make(Schedule::class);
            $schedule->command('booking:cek-notif')->everyMinute();
        });
    })
    ->create();
