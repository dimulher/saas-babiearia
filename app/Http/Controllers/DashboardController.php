<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Agendamento;
use App\Models\Profissional;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $barbeariaId  = $user->barbearia_id;
        $barbearia    = $user->barbearia;
        $selectedDate = request('date') ? Carbon::parse(request('date')) : now();
        $hoje         = $selectedDate->toDateString();
        $mesStart     = now()->startOfMonth();
        $mesEnd       = now()->endOfMonth();

        // ── Query 1: todos os contadores + faturamento em uma única passagem
        $stats = DB::table(DB::raw('(SELECT 1) as dual'))
            ->selectRaw("
                (SELECT COUNT(*) FROM agendamentos WHERE barbearia_id = ? AND DATE(data_inicio) = ? AND status != 'cancelado') as agendamentos_hoje,
                (SELECT COUNT(*) FROM agendamentos WHERE barbearia_id = ? AND data_inicio BETWEEN ? AND ?) as agendamentos_mes,
                (SELECT COUNT(*) FROM agendamentos WHERE barbearia_id = ? AND status = 'agendado' AND DATE(data_inicio) >= ?) as agendamentos_pendentes,
                (SELECT COALESCE(SUM(preco),0) FROM agendamentos WHERE barbearia_id = ? AND DATE(data_inicio) = ? AND status = 'concluido') as faturamento_hoje,
                (SELECT COALESCE(SUM(preco),0) FROM agendamentos WHERE barbearia_id = ? AND data_inicio BETWEEN ? AND ? AND status = 'concluido') as faturamento_mes,
                (SELECT COUNT(*) FROM clientes WHERE barbearia_id = ?) as clientes_ativos,
                (SELECT COUNT(*) FROM profissionais WHERE barbearia_id = ? AND ativo = true) as total_profissionais,
                (SELECT COUNT(*) FROM servicos WHERE barbearia_id = ? AND ativo = true) as total_servicos
            ", [
                $barbeariaId, $hoje,
                $barbeariaId, $mesStart, $mesEnd,
                $barbeariaId, $hoje,
                $barbeariaId, $hoje,
                $barbeariaId, $mesStart, $mesEnd,
                $barbeariaId,
                $barbeariaId,
                $barbeariaId,
            ])->first();

        $faturamentoMes  = $stats->faturamento_mes  ?? 0;
        $agendamentosMes = $stats->agendamentos_mes ?? 0;
        $ticketMedio     = $agendamentosMes > 0 ? $faturamentoMes / $agendamentosMes : 0;

        // ── Query 2: agendamentos do dia selecionado ──────────────────────
        $agendamentos = Agendamento::where('barbearia_id', $barbeariaId)
            ->whereDate('data_inicio', $hoje)
            ->with(['servico:id,nome', 'profissional:id,nome'])
            ->orderBy('data_inicio')
            ->get();

        // ── Query 3: profissionais para links de agendamento ──────────────
        $profissionaisList = Profissional::where('barbearia_id', $barbeariaId)
            ->where('ativo', true)
            ->where('aceita_agendamento_online', true)
            ->select('id', 'nome')
            ->get();

        return view('panel.dashboard', [
            'agendamentosHoje'      => $stats->agendamentos_hoje      ?? 0,
            'agendamentosMes'       => $agendamentosMes,
            'agendamentosPendentes' => $stats->agendamentos_pendentes ?? 0,
            'faturamentoHoje'       => $stats->faturamento_hoje       ?? 0,
            'faturamentoMes'        => $faturamentoMes,
            'ticketMedio'           => $ticketMedio,
            'clientesAtivos'        => $stats->clientes_ativos        ?? 0,
            'profissionais'         => $stats->total_profissionais    ?? 0,
            'servicos'              => $stats->total_servicos         ?? 0,
            'agendamentos'          => $agendamentos,
            'profissionaisList'     => $profissionaisList,
            'selectedDate'          => $selectedDate,
            'barbearia'             => $barbearia,
        ]);
    }
}
