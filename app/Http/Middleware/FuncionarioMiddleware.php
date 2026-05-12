<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuncionarioMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Dono/gerente não acessa painel de funcionário
        if (in_array(Auth::user()->role, ['admin', 'gerente'])) {
            return redirect()->route('panel.dashboard');
        }

        return $next($request);
    }
}
