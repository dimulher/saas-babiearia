<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        $mes = $request->get('mes', now()->format('Y-m'));
        $start = \Carbon\Carbon::parse($mes)->startOfMonth();
        $end = \Carbon\Carbon::parse($mes)->endOfMonth();

        $barbeariaId = $user->barbearia_id;

        // 1. Relatório de Serviços
        $servicosPerformance = DB::table('comanda_itens')
            ->join('comandas', 'comanda_itens.comanda_id', '=', 'comandas.id')
            ->join('servicos', 'comanda_itens.servico_id', '=', 'servicos.id')
            ->where('comandas.barbearia_id', $barbeariaId)
            ->where('comandas.status', 'fechada')
            ->whereBetween('comandas.fechada_em', [$start, $end])
            ->select('servicos.nome', DB::raw('sum(comanda_itens.quantidade) as qtd'), DB::raw('sum(comanda_itens.subtotal) as receita'))
            ->groupBy('servicos.id', 'servicos.nome')
            ->orderByDesc('qtd')
            ->get();

        $totalReceitaServicos = $servicosPerformance->sum('receita');
        $servicoMaisVendido = $servicosPerformance->first();

        // 2. Relatório de Clientes
        $clientesRanking = DB::table('comandas')
            ->join('clientes', 'comandas.cliente_id', '=', 'clientes.id')
            ->where('comandas.barbearia_id', $barbeariaId)
            ->where('comandas.status', 'fechada')
            ->whereBetween('comandas.fechada_em', [$start, $end])
            ->select('clientes.nome', DB::raw('count(*) as visitas'), DB::raw('sum(comandas.total) as gasto_total'))
            ->groupBy('clientes.id', 'clientes.nome')
            ->orderByDesc('visitas')
            ->limit(10)
            ->get();

        $clientesNovos = Cliente::where('barbearia_id', $barbeariaId)->whereBetween('created_at', [$start, $end])->count();
        $clientesAtivos = Cliente::where('barbearia_id', $barbeariaId)->count();

        // 3. Relatório de Profissionais
        $profissionaisPerformance = DB::table('comandas')
            ->join('profissionais', 'comandas.profissional_id', '=', 'profissionais.id')
            ->where('comandas.barbearia_id', $barbeariaId)
            ->where('comandas.status', 'fechada')
            ->whereBetween('comandas.fechada_em', [$start, $end])
            ->select('profissionais.nome', DB::raw('count(*) as atendimentos'), DB::raw('sum(comandas.total) as receita_gerada'))
            ->groupBy('profissionais.id', 'profissionais.nome')
            ->orderByDesc('atendimentos')
            ->get();

        return view('panel.relatorios.index', compact(
            'servicosPerformance',
            'totalReceitaServicos',
            'servicoMaisVendido',
            'clientesRanking',
            'clientesNovos',
            'clientesAtivos',
            'profissionaisPerformance',
            'mes'
        ));
    }
}
