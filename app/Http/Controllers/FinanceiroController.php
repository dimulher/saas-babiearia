<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Comanda;
use App\Models\Profissional;
use App\Models\ComandaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        $barbeariaId = $user->barbearia_id;

        // Período selecionado
        [$start, $end] = $this->resolverPeriodo($request);

        $hojeStart = now()->startOfDay();
        $hojeEnd   = now()->endOfDay();
        $mesStart  = now()->startOfMonth();
        $mesEnd    = now()->endOfMonth();

        // ── Query 1: Contas pagas (entradas + saídas) no período ──────────
        $contas = Conta::where('barbearia_id', $barbeariaId)
            ->where('status', 'pago')
            ->whereBetween('pago_em', [$start, $end])
            ->get(['pago_em', 'descricao', 'categoria', 'tipo', 'valor']);

        // ── Query 2: Comandas fechadas no período ─────────────────────────
        $comandas = Comanda::where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end])
            ->get(['id', 'fechada_em', 'cliente_nome', 'total', 'forma_pagamento', 'profissional_id']);

        // ── Query 3: Resumos financeiros (contas + comandas) em uma única round-trip ──
        $r = DB::selectOne("
            SELECT
                (SELECT COALESCE(SUM(CASE WHEN tipo='receita' AND pago_em BETWEEN ? AND ? THEN valor ELSE 0 END),0)
                    FROM contas WHERE barbearia_id = ? AND status = 'pago') as receitas_hoje,
                (SELECT COALESCE(SUM(CASE WHEN tipo='despesa' AND pago_em BETWEEN ? AND ? THEN valor ELSE 0 END),0)
                    FROM contas WHERE barbearia_id = ? AND status = 'pago') as despesas_hoje,
                (SELECT COALESCE(SUM(CASE WHEN tipo='receita' AND pago_em BETWEEN ? AND ? THEN valor ELSE 0 END),0)
                    FROM contas WHERE barbearia_id = ? AND status = 'pago') as receitas_mes,
                (SELECT COALESCE(SUM(CASE WHEN tipo='despesa' AND pago_em BETWEEN ? AND ? THEN valor ELSE 0 END),0)
                    FROM contas WHERE barbearia_id = ? AND status = 'pago') as despesas_mes,
                (SELECT COALESCE(SUM(CASE WHEN fechada_em BETWEEN ? AND ? THEN total ELSE 0 END),0)
                    FROM comandas WHERE barbearia_id = ? AND status = 'fechada') as comanda_hoje,
                (SELECT COALESCE(SUM(CASE WHEN fechada_em BETWEEN ? AND ? THEN total ELSE 0 END),0)
                    FROM comandas WHERE barbearia_id = ? AND status = 'fechada') as comanda_mes
        ", [
            $hojeStart, $hojeEnd, $barbeariaId,
            $hojeStart, $hojeEnd, $barbeariaId,
            $mesStart,  $mesEnd,  $barbeariaId,
            $mesStart,  $mesEnd,  $barbeariaId,
            $hojeStart, $hojeEnd, $barbeariaId,
            $mesStart,  $mesEnd,  $barbeariaId,
        ]);

        $resumoHoje = [
            'receitas' => ($r->receitas_hoje ?? 0) + ($r->comanda_hoje ?? 0),
            'despesas' => $r->despesas_hoje ?? 0,
            'lucro'    => (($r->receitas_hoje ?? 0) + ($r->comanda_hoje ?? 0)) - ($r->despesas_hoje ?? 0),
        ];
        $resumoMes = [
            'receitas' => ($r->receitas_mes ?? 0) + ($r->comanda_mes ?? 0),
            'despesas' => $r->despesas_mes ?? 0,
            'lucro'    => (($r->receitas_mes ?? 0) + ($r->comanda_mes ?? 0)) - ($r->despesas_mes ?? 0),
        ];

        // ── Movimentações mescladas ───────────────────────────────────────
        $movimentacoes = $contas->map(fn($c) => [
            'data'            => $c->pago_em,
            'descricao'       => $c->descricao,
            'categoria'       => $c->categoria ?? 'Geral',
            'tipo'            => $c->tipo,
            'valor'           => $c->valor,
            'forma_pagamento' => '-',
        ])->concat($comandas->map(fn($c) => [
            'data'            => $c->fechada_em,
            'descricao'       => "Comanda #{$c->id} - {$c->cliente_nome}",
            'categoria'       => 'Serviços/Vendas',
            'tipo'            => 'receita',
            'valor'           => $c->total,
            'forma_pagamento' => $c->forma_pagamento,
        ]))->sortByDesc('data');

        $totalEntradas = $movimentacoes->where('tipo', 'receita')->sum('valor');
        $totalSaidas   = $movimentacoes->where('tipo', 'despesa')->sum('valor');
        $saldo         = $totalEntradas - $totalSaidas;

        // ── Query 4: Faturamento por profissional + gráfico diário + serviços ──
        $profissionalId = $request->profissional_id;

        $faturamentoPorProfissional = DB::table('comandas')
            ->join('profissionais', 'comandas.profissional_id', '=', 'profissionais.id')
            ->where('comandas.barbearia_id', $barbeariaId)
            ->where('comandas.status', 'fechada')
            ->whereBetween('comandas.fechada_em', [$start, $end])
            ->selectRaw('profissionais.id, profissionais.nome, SUM(comandas.total) as total')
            ->groupBy('profissionais.id', 'profissionais.nome')
            ->orderByDesc('total')
            ->get();

        $graficoQuery = DB::table('comandas')
            ->where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end]);
        if ($profissionalId) $graficoQuery->where('profissional_id', $profissionalId);

        $faturamentoDiario = $graficoQuery
            ->selectRaw('DATE(fechada_em) as data, SUM(total) as total')
            ->groupBy('data')->orderBy('data')->get();

        $servicosDistribuiucao = DB::table('comanda_itens')
            ->join('comandas', 'comanda_itens.comanda_id', '=', 'comandas.id')
            ->where('comandas.barbearia_id', $barbeariaId)
            ->where('comandas.status', 'fechada')
            ->whereBetween('comandas.fechada_em', [$start, $end])
            ->when($profissionalId, fn($q) => $q->where('comandas.profissional_id', $profissionalId))
            ->selectRaw('comanda_itens.descricao, COUNT(*) as qtd, SUM(comanda_itens.subtotal) as total')
            ->groupBy('comanda_itens.descricao')
            ->orderByDesc('qtd')
            ->limit(5)
            ->get();

        $profissionais = Profissional::where('barbearia_id', $barbeariaId)
            ->select('id', 'nome')->get();

        $contasLista = Conta::where('barbearia_id', $barbeariaId)
            ->latest('vencimento')
            ->limit(200)
            ->get();

        return view('panel.financeiro.index', compact(
            'movimentacoes', 'totalEntradas', 'totalSaidas', 'saldo',
            'start', 'end', 'resumoMes', 'resumoHoje',
            'faturamentoPorProfissional', 'profissionais',
            'faturamentoDiario', 'servicosDistribuiucao',
            'contasLista'
        ));
    }

    private function resolverPeriodo(Request $request): array
    {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();

        if ($request->periodo === 'hoje') {
            $start = now()->startOfDay();
            $end   = now()->endOfDay();
        } elseif ($request->periodo === 'semana') {
            $start = now()->startOfWeek();
            $end   = now()->endOfWeek();
        } elseif ($request->periodo === 'personalizado' && $request->de && $request->ate) {
            $start = Carbon::parse($request->de)->startOfDay();
            $end   = Carbon::parse($request->ate)->endOfDay();
        }

        return [$start, $end];
    }
}
