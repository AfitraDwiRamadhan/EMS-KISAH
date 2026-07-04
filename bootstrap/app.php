<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e) {
            if (isset($_SERVER['VERCEL_URL']) || str_starts_with(dirname(__DIR__), '/var/task')) {
                echo "<h1>Original Exception</h1>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                exit(1);
            }
        });
    })->create();

if (isset($_SERVER['VERCEL_URL']) || str_starts_with(base_path(), '/var/task')) {
    $app->useBootstrapPath('/tmp/bootstrap');
}

return $app;
