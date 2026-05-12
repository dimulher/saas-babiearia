<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbearia;
use App\Models\User;
use App\Models\Agendamento;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBarbearias   = Barbearia::count();
        $barberiasAtivas   = Barbearia::where('ativo', true)->count();
        $totalUsuarios     = User::count();
        $totalAgendamentos = Agendamento::count();

        $barbearias = Barbearia::withCount(['users', 'agendamentos'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $planos = Barbearia::select('plano', DB::raw('count(*) as total'))
            ->groupBy('plano')
            ->pluck('total', 'plano');

        $recentes = Barbearia::orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBarbearias',
            'barberiasAtivas',
            'totalUsuarios',
            'totalAgendamentos',
            'barbearias',
            'planos',
            'recentes'
        ));
    }

    public function barbearias()
    {
        $query = Barbearia::withCount(['users', 'agendamentos', 'clientes'])
            ->orderByDesc('created_at');

        if ($busca = request('busca')) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'ilike', "%{$busca}%")
                  ->orWhere('email', 'ilike', "%{$busca}%");
            });
        }
        if ($plano = request('plano')) {
            $query->where('plano', $plano);
        }
        if (request()->has('ativo') && request('ativo') !== '') {
            $query->where('ativo', (bool) request('ativo'));
        }

        $barbearias = $query->paginate(20);

        return view('admin.barbearias', compact('barbearias'));
    }

    public function usuarios()
    {
        $query = User::with('barbearia')->orderByDesc('created_at');

        if ($busca = request('busca')) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'ilike', "%{$busca}%")
                  ->orWhere('email', 'ilike', "%{$busca}%");
            });
        }
        if ($role = request('role')) {
            $query->where('role', $role);
        }

        $usuarios = $query->paginate(20);

        $totais = [
            'admin'    => User::where('role', 'admin')->count(),
            'gerente'  => User::where('role', 'gerente')->count(),
            'barbeiro' => User::where('role', 'barbeiro')->count(),
        ];

        return view('admin.usuarios', compact('usuarios', 'totais'));
    }

    public function impersonar($barbeariaId)
    {
        $barbearia = Barbearia::findOrFail($barbeariaId);
        $user = User::where('barbearia_id', $barbeariaId)
            ->where('role', 'admin')
            ->firstOrFail();

        Auth()->login($user);

        return redirect()->route('panel.dashboard')
            ->with('success', "Acessando como: {$barbearia->nome}");
    }
}
