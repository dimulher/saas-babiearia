<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        // Endpoint chamado pelo Make.com (sem sessão/CSRF) — autenticado por token compartilhado
        $middleware->validateCsrfTokens(except: [
            'webhooks/google-calendar/sync',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redireciona para login em vez de mostrar erro JSON
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, Illuminate\Http\Request $request) {
            return redirect()->route('login');
        });

        // Mostra o erro real para diagnóstico
        $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
            return response()->json([
                'error' => $e->getMessage(),
                'file'  => str_replace(base_path(), '', $e->getFile()),
                'line'  => $e->getLine(),
            ], 500);
        });
    })->create();
