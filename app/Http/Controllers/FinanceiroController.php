<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Comanda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceiroController
{
    public function index(Request $request)
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        if ($request->periodo == 'hoje') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        } elseif ($request->periodo == 'semana') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
        } elseif ($request->periodo == 'personalizado' && $request->de && $request->ate) {
            $start = Carbon::parse($request->de)->startOfDay();
            $end = Carbon::parse($request->ate)->endOfDay();
        }

        $barbeariaId = auth()->user()->barbearia_id;

        // Buscar Contas Pagas (Entradas e Saídas)
        $contas = Conta::where('barbearia_id', $barbeariaId)
            ->where('status', 'pago')
            ->whereBetween('pago_em', [$start, $end])
            ->get()
            ->map(function ($item) {
                return [
                    'data' => $item->pago_em,
                    'descricao' => $item->descricao,
                    'categoria' => $item->categoria ?? 'Geral',
                    'tipo' => $item->tipo, // receita ou despesa
                    'valor' => $item->valor,
                    'forma_pagamento' => '-',
                ];
            });

        // Buscar Comandas Fechadas (Apenas Entradas)
        $comandas = Comanda::where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end])
            ->get()
            ->map(function ($item) {
                return [
                    'data' => $item->fechada_em,
                    'descricao' => "Comanda #{$item->id} - {$item->cliente_nome}",
                    'categoria' => 'Serviços/Vendas',
                    'tipo' => 'receita',
                    'valor' => $item->total,
                    'forma_pagamento' => $item->forma_pagamento,
                ];
            });

        // Dados para Gestão de Caixa (Hoje)
        $hojeStart = now()->startOfDay();
        $hojeEnd = now()->endOfDay();
        
        $receitasHoje = Conta::where('barbearia_id', $barbeariaId)->where('status', 'pago')->where('tipo', 'receita')->whereBetween('pago_em', [$hojeStart, $hojeEnd])->sum('valor') + 
                        Comanda::where('barbearia_id', $barbeariaId)->where('status', 'fechada')->whereBetween('fechada_em', [$hojeStart, $hojeEnd])->sum('total');
        
        $despesasHoje = Conta::where('barbearia_id', $barbeariaId)->where('status', 'pago')->where('tipo', 'despesa')->whereBetween('pago_em', [$hojeStart, $hojeEnd])->sum('valor');

        $resumoHoje = [
            'receitas' => $receitasHoje,
            'despesas' => $despesasHoje,
            'lucro' => $receitasHoje - $despesasHoje
        ];

        // Mesclar e Ordenar
        $movimentacoes = $contas->concat($comandas)->sortByDesc('data');

        $totalEntradas = $movimentacoes->where('tipo', 'receita')->sum('valor');
        $totalSaidas = $movimentacoes->where('tipo', 'despesa')->sum('valor');
        $saldo = $totalEntradas - $totalSaidas;

        // Resumo Mensal para Visão Geral
        $mesAtualStart = now()->startOfMonth();
        $mesAtualEnd = now()->endOfMonth();
        
        $receitasMes = Conta::where('barbearia_id', $barbeariaId)->where('status', 'pago')->where('tipo', 'receita')->whereBetween('pago_em', [$mesAtualStart, $mesAtualEnd])->sum('valor') + 
                      Comanda::where('barbearia_id', $barbeariaId)->where('status', 'fechada')->whereBetween('fechada_em', [$mesAtualStart, $mesAtualEnd])->sum('total');
        
        $despesasMes = Conta::where('barbearia_id', $barbeariaId)->where('status', 'pago')->where('tipo', 'despesa')->whereBetween('pago_em', [$mesAtualStart, $mesAtualEnd])->sum('valor');

        $resumoMes = [
            'receitas' => $receitasMes,
            'despesas' => $despesasMes,
            'lucro' => $receitasMes - $despesasMes
        ];

        // Faturamento por Profissional (Ranking)
        $faturamentoPorProfissional = Comanda::where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end])
            ->selectRaw('profissional_id, SUM(total) as total')
            ->groupBy('profissional_id')
            ->with('profissional:id,nome')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->profissional_id,
                    'nome' => $item->profissional ? $item->profissional->nome : 'Geral/Outros',
                    'total' => (float) $item->total
                ];
            })
            ->sortByDesc('total');

        // Dados Detalhados para Gráficos
        $profissionais = \App\Models\Profissional::where('barbearia_id', $barbeariaId)->get();
        $profissionalId = $request->profissional_id;

        $queryGrafico = Comanda::where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end]);

        if ($profissionalId) {
            $queryGrafico->where('profissional_id', $profissionalId);
        }

        $faturamentoDiario = $queryGrafico->clone()
            ->selectRaw('DATE(fechada_em) as data, SUM(total) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        // Distribuição de Serviços (Top 5)
        $itensServicos = \App\Models\ComandaItem::whereHas('comanda', function($q) use ($start, $end, $profissionalId, $barbeariaId) {
                $q->where('barbearia_id', $barbeariaId)
                  ->where('status', 'fechada')
                  ->whereBetween('fechada_em', [$start, $end]);
                if ($profissionalId) $q->where('profissional_id', $profissionalId);
            })
            ->selectRaw('descricao, COUNT(*) as qtd, SUM(subtotal) as total')
            ->groupBy('descricao')
            ->get();

        // Verificar comandas sem itens mas com valor total (Venda Avulsa)
        $comandasSemItens = Comanda::where('barbearia_id', $barbeariaId)
            ->where('status', 'fechada')
            ->whereBetween('fechada_em', [$start, $end])
            ->whereDoesntHave('itens')
            ->where('total', '>', 0);
        
        if ($profissionalId) {
            $comandasSemItens->where('profissional_id', $profissionalId);
        }

        $totalSemItens = $comandasSemItens->sum('total');
        $qtdSemItens = $comandasSemItens->count();

        if ($totalSemItens > 0) {
            $itensServicos->push((object)[
                'descricao' => 'Venda Manual / Avulsa',
                'qtd' => $qtdSemItens,
                'total' => $totalSemItens
            ]);
        }

        $servicosDistribuiucao = $itensServicos->sortByDesc('qtd')->take(5);

        return view('panel.financeiro.index', compact(
            'movimentacoes', 
            'totalEntradas', 
            'totalSaidas', 
            'saldo', 
            'start', 
            'end',
            'resumoMes',
            'resumoHoje',
            'faturamentoPorProfissional',
            'profissionais',
            'faturamentoDiario',
            'servicosDistribuiucao'
        ));
    }
}
