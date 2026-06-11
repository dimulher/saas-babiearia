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

        // Rotas sem CSRF:
        // - webhooks/google-calendar/sync: chamado pelo Make.com, autenticado por token próprio
        // - rotas de auth: o runtime vercel-php não round-tripa cookies de sessão de forma confiável,
        //   tornando CSRF inviável nessas rotas; o risco de login-CSRF é baixo comparado às rotas
        //   autenticadas do painel, que mantêm proteção CSRF normalmente
        $middleware->validateCsrfTokens(except: [
            'webhooks/google-calendar/sync',
            'login/proprietario',
            'register',
            'logout',
            'funcionario/login',
            'forgot-password',
            'reset-password',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redireciona para login em vez de mostrar erro JSON
        // Usa redirect('/login') e não route('login') para evitar problemas com APP_URL mal configurado
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, Illuminate\Http\Request $request) {
            return redirect('/login');
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
