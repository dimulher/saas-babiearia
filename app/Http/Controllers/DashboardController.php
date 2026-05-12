<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;

class DashboardController extends Controller
{
    public function index()
    {
        $barbeariaId = Auth::user()->barbearia_id;

        $agendamentosHoje = Agendamento::where('barbearia_id', $barbeariaId)
            ->whereDate('data_inicio', today())
            ->whereNotIn('status', ['cancelado'])
            ->count();

        $clientesAtivos = Cliente::where('barbearia_id', $barbeariaId)
            ->where('ativo', true)
            ->count();

        $profissionais = Profissional::where('barbearia_id', $barbeariaId)
            ->where('ativo', true)
            ->count();

        $servicos = Servico::where('barbearia_id', $barbeariaId)
            ->where('ativo', true)
            ->count();

        $proximosAgendamentos = Agendamento::with(['cliente', 'profissional', 'servico'])
            ->where('barbearia_id', $barbeariaId)
            ->where('data_inicio', '>=', now())
            ->whereNotIn('status', ['cancelado'])
            ->orderBy('data_inicio')
            ->take(10)
            ->get();

        $agendamentosAnteriores = Agendamento::with(['cliente', 'profissional', 'servico'])
            ->where('barbearia_id', $barbeariaId)
            ->where('data_inicio', '<', now())
            ->orderByDesc('data_inicio')
            ->take(5)
            ->get();

        $listaProfissionais = Profissional::where('barbearia_id', $barbeariaId)
            ->where('ativo', true)
            ->get();

        return view('panel.dashboard', compact(
            'agendamentosHoje',
            'clientesAtivos',
            'profissionais',
            'servicos',
            'proximosAgendamentos',
            'agendamentosAnteriores',
            'listaProfissionais'
        ));
    }
}
