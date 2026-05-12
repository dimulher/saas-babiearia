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
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redireciona para login em vez de mostrar erro JSON
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, Illuminate\Http\Request $request) {
            return redirect()->route('login');
        });

        // Só retorna JSON em produção para erros não-auth (evita stack trace público)
        $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
            if (app()->environment('local')) return null; // usa handler padrão em dev
            return response()->json(['error' => 'Erro interno do servidor.'], 500);
        });
    })->create();
