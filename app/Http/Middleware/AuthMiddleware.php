<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faça login para continuar.');
        }

        // Barbeiro não acessa o painel do dono — vai para área dele
        if (Auth::user()->role === 'barbeiro') {
            return redirect()->route('funcionario.dashboard');
        }

        return $next($request);
    }
}
