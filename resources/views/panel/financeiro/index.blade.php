@extends('layouts.app')
@section('title', 'Financeiro')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: '{{ request('tab', 'visao-geral') }}',
    periodo: '{{ request('periodo', 'mes') }}',
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    init() {
        this.$nextTick(() => requestAnimationFrame(() => glowInitChart(this.activeTab)));
        this.$watch('activeTab', tab => this.$nextTick(() => requestAnimationFrame(() => glowInitChart(tab))));
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Financeiro</h1>
            <p class="text-sm text-gray-500 mt-1">Gestão completa das movimentações do seu estabelecimento.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-1 p-1 bg-gray-900 rounded-xl border border-gray-800 w-max min-w-full">
            @foreach([['visao-geral','Visão Geral'],['movimentacoes','Extrato'],['funcionarios','Equipe'],['caixa','Caixa'],['contas','Contas'],['relatorios','Relatórios']] as [$tab, $label])
            <button @click="activeTab = '{{ $tab }}'" :class="activeTab === '{{ $tab }}' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-4 sm:px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <!-- 1. Visão Geral -->
    <div x-show="activeTab === 'visao-geral'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6 border-l-4 border-l-emerald-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Receitas do Mês</p>
                <p class="text-2xl sm:text-3xl font-bold text-emerald-400 mt-2">R$ {{ number_format($resumoMes['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6 border-l-4 border-l-rose-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Despesas do Mês</p>
                <p class="text-2xl sm:text-3xl font-bold text-rose-400 mt-2">R$ {{ number_format($resumoMes['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-4 sm:p-6 shadow-xl shadow-green-900/30 text-white">
                <p class="text-[10px] text-green-200 font-bold uppercase tracking-widest">Lucro Estimado</p>
                <p class="text-2xl sm:text-3xl font-bold mt-2">R$ {{ number_format($resumoMes['lucro'], 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <h2 class="text-sm font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tight">
                    <i class="fa-solid fa-chart-line text-green-500"></i> Fluxo de Caixa
                </h2>
                <div class="h-48 sm:h-64">
                    <canvas id="chartFluxoCaixa"></canvas>
                </div>
            </div>

            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2 uppercase tracking-tight">
                        <i class="fa-solid fa-users text-green-500"></i> Desempenho da Equipe
                    </h2>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mês Atual</span>
                </div>
                <div class="space-y-5">
                    @forelse($faturamentoPorProfissional as $fat)
                        @php $porcentagem = $totalEntradas > 0 ? ($fat['total'] / $totalEntradas) * 100 : 0; @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-bold text-gray-300">{{ $fat['nome'] }}</span>
                                <span class="text-sm font-bold text-white">R$ {{ number_format($fat['total'], 2, ',', '.') }}</span>
                            </div>
                            <div class="h-2 w-full bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $porcentagem }}%"></div>
                            </div>
                            <div class="flex justify-between text-[9px] font-bold text-gray-500 uppercase tracking-widest">
                                <span>Participação no faturamento</span>
                                <span>{{ number_format($porcentagem, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                            <i class="fa-solid fa-user-slash text-3xl mb-2 block"></i>
                            <p class="text-xs font-medium">Nenhum dado disponível.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Terceiro gráfico: Distribuição Financeira do Mês --}}
        @php
            $receitasDia = $movimentacoes->where('tipo','receita')
                ->groupBy(fn($m) => $m['data']->format('Y-m-d'))
                ->map(fn($g) => round($g->sum('valor'), 2))
                ->sortKeys();
            $despesasDia = $movimentacoes->where('tipo','despesa')
                ->groupBy(fn($m) => $m['data']->format('Y-m-d'))
                ->map(fn($g) => round($g->sum('valor'), 2))
                ->sortKeys();
            $datas = $receitasDia->keys()->merge($despesasDia->keys())->unique()->sort()->values();
            $taxa = $resumoMes['receitas'] > 0
                ? round(($resumoMes['lucro'] / $resumoMes['receitas']) * 100, 1) : 0;
        @endphp

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
            <h2 class="text-sm font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tight">
                <i class="fa-solid fa-chart-pie text-amber-400"></i> Distribuição Financeira
                <span class="ml-auto text-[9px] font-bold text-gray-500 uppercase tracking-widest normal-case">Mês atual</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 items-center">
                {{-- Donut --}}
                <div class="sm:col-span-2 h-52 flex items-center justify-center">
                    <canvas id="chartDistribuicao"></canvas>
                </div>

                {{-- Stats --}}
                <div class="sm:col-span-3 space-y-3">
                    <div class="flex items-center justify-between px-5 py-4 bg-emerald-900/10 border border-emerald-800/20 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                            <div>
                                <p class="text-xs font-extrabold text-white">Receitas</p>
                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Entradas do mês</p>
                            </div>
                        </div>
                        <span class="text-base font-extrabold text-emerald-400">
                            R$ {{ number_format($resumoMes['receitas'], 2, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-5 py-4 bg-rose-900/10 border border-rose-800/20 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                            <div>
                                <p class="text-xs font-extrabold text-white">Despesas</p>
                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Saídas do mês</p>
                            </div>
                        </div>
                        <span class="text-base font-extrabold text-rose-400">
                            R$ {{ number_format($resumoMes['despesas'], 2, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-5 py-4 bg-gray-900/60 border border-gray-800/50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full {{ $resumoMes['lucro'] >= 0 ? 'bg-green-400' : 'bg-rose-400' }} shrink-0"></span>
                            <div>
                                <p class="text-xs font-extrabold text-white">Resultado Líquido</p>
                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">
                                    Lucratividade: {{ $taxa }}%
                                </p>
                            </div>
                        </div>
                        <span class="text-base font-extrabold {{ $resumoMes['lucro'] >= 0 ? 'text-green-400' : 'text-rose-400' }}">
                            R$ {{ number_format($resumoMes['lucro'], 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 2. Movimentações -->
    <div x-show="activeTab === 'movimentacoes'" x-cloak class="space-y-5">
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
            <form action="{{ route('panel.financeiro.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <input type="hidden" name="tab" value="movimentacoes">
                <div class="flex-1 space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Período</label>
                    <select name="periodo" x-model="periodo" class="block w-full px-4 py-3 border border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-green-500/50 outline-none bg-gray-900 text-white">
                        <option value="mes">Este Mês</option>
                        <option value="hoje">Hoje</option>
                        <option value="semana">Esta Semana</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                <div x-show="periodo === 'personalizado'" class="flex gap-2 flex-1">
                    <div class="flex-1 space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">De</label>
                        <input type="date" name="de" value="{{ request('de') }}" class="block w-full px-4 py-3 border border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-green-500/50 outline-none bg-gray-900 text-white">
                    </div>
                    <div class="flex-1 space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Até</label>
                        <input type="date" name="ate" value="{{ request('ate') }}" class="block w-full px-4 py-3 border border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-green-500/50 outline-none bg-gray-900 text-white">
                    </div>
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all w-full sm:w-auto">
                    Aplicar Filtros
                </button>
            </form>
        </div>

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-3 sm:px-6 py-4">Data/Hora</th>
                            <th class="px-3 sm:px-6 py-4">Lançamento</th>
                            <th class="px-3 sm:px-6 py-4">Categoria</th>
                            <th class="px-3 sm:px-6 py-4 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($movimentacoes as $mov)
                            <tr class="hover:bg-gray-900/40 transition-colors">
                                <td class="px-3 sm:px-6 py-4 text-gray-400 text-xs">{{ $mov['data']->format('d/m/Y H:i') }}</td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div class="font-semibold text-white text-sm">{{ $mov['descricao'] }}</div>
                                    <div class="text-[10px] text-green-400 font-bold uppercase tracking-tight mt-0.5">{{ $mov['forma_pagamento'] }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-800 text-gray-400 rounded-lg text-[9px] font-bold uppercase tracking-widest">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-right font-bold {{ $mov['tipo'] == 'receita' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $mov['tipo'] == 'receita' ? '+' : '-' }} R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                <i class="fa-solid fa-receipt text-3xl mb-3 text-gray-700 block"></i>
                                <p class="text-xs font-bold uppercase tracking-widest">Nenhuma movimentação</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Equipe -->
    <div x-show="activeTab === 'funcionarios'" x-cloak class="space-y-5">
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
            <form action="{{ route('panel.financeiro.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                <input type="hidden" name="tab" value="funcionarios">
                <div class="flex-1 space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Especialista</label>
                    <select name="profissional_id" class="block w-full px-4 py-3 border border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-green-500/50 outline-none bg-gray-900 text-white">
                        <option value="">Equipe Completa</option>
                        @foreach($profissionais as $prof)
                            <option value="{{ $prof->id }}" {{ request('profissional_id') == $prof->id ? 'selected' : '' }}>{{ $prof->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Período</label>
                    <select name="periodo" x-model="periodo" class="block w-full px-4 py-3 border border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-green-500/50 outline-none bg-gray-900 text-white">
                        <option value="mes">Este Mês</option>
                        <option value="hoje">Hoje</option>
                        <option value="semana">Esta Semana</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all w-full lg:w-auto shadow-lg shadow-green-900/20">
                    Atualizar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Faturamento</p>
                <p class="text-3xl font-bold text-white mt-2">R$ {{ number_format($faturamentoDiario->sum('total'), 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Serviços</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $servicosDistribuiucao->sum('qtd') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6 col-span-2 lg:col-span-1">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ticket Médio</p>
                @php $qtdTotal = $servicosDistribuiucao->sum('qtd'); @endphp
                <p class="text-3xl font-bold text-white mt-2">R$ {{ $qtdTotal > 0 ? number_format($faturamentoDiario->sum('total') / $qtdTotal, 2, ',', '.') : '0,00' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white uppercase mb-6 flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-chart-line text-green-500"></i> Evolução de Receita
                </h3>
                <div class="h-56 sm:h-72"><canvas id="chartEvolucao"></canvas></div>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white uppercase mb-6 flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-magic-wand-sparkles text-amber-400"></i> Principais Serviços
                </h3>
                <div class="h-44 sm:h-56 mb-6"><canvas id="chartServicos"></canvas></div>
                <div class="space-y-3">
                    @foreach($servicosDistribuiucao as $serv)
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase">
                            <span class="text-gray-400 truncate w-28">{{ $serv->descricao }}</span>
                            <span class="text-white bg-gray-800 px-2 py-0.5 rounded">{{ $serv->qtd }} un.</span>
                            <span class="text-green-400">R$ {{ number_format($serv->total, 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- 4. Caixa -->
    <div x-show="activeTab === 'caixa'" x-cloak class="space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 uppercase tracking-tight">
                <i class="fa-solid fa-cash-register text-gray-400"></i> Fluxo do Dia
            </h2>
            <div class="flex flex-wrap gap-2">
                <button class="bg-[#111827] border border-gray-800 text-gray-300 px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 flex items-center gap-2 transition-all rounded-xl">
                    <i class="fa-solid fa-arrow-up text-emerald-400"></i> Entrada
                </button>
                <button class="bg-[#111827] border border-gray-800 text-gray-300 px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 flex items-center gap-2 transition-all rounded-xl">
                    <i class="fa-solid fa-arrow-down text-rose-400"></i> Saída
                </button>
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">Fechar Caixa</button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Saldo Atual</p>
                <p class="text-xl sm:text-3xl font-bold text-white mt-2">R$ {{ number_format($resumoHoje['lucro'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6">
                <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">Entradas</p>
                <p class="text-xl sm:text-3xl font-bold text-emerald-400 mt-2">R$ {{ number_format($resumoHoje['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-6">
                <p class="text-[10px] text-rose-400 font-bold uppercase tracking-widest">Saídas</p>
                <p class="text-xl sm:text-3xl font-bold text-rose-400 mt-2">R$ {{ number_format($resumoHoje['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-4 sm:p-6 shadow-xl text-white">
                <p class="text-[10px] text-green-200 font-bold uppercase tracking-widest">Status do Caixa</p>
                <p class="text-lg font-bold mt-2 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-green-300 rounded-full animate-pulse"></span> Aberto
                </p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-800/50 flex justify-between items-center">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-list-ul"></i> Movimentações de Hoje
                </h3>
                <span class="text-[9px] font-bold text-gray-400 bg-gray-900 px-3 py-1.5 rounded-full border border-gray-800 uppercase tracking-widest">{{ now()->format('d M, Y') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <tbody class="divide-y divide-gray-800/50">
                        @php $hojeMov = $movimentacoes->filter(fn($m) => $m['data']->isToday()); @endphp
                        @forelse($hojeMov as $mov)
                            <tr class="hover:bg-gray-900/40 transition-colors">
                                <td class="px-3 sm:px-6 py-4 text-gray-400 font-bold text-xs w-20">{{ $mov['data']->format('H:i') }}</td>
                                <td class="px-3 sm:px-6 py-4">
                                    <span class="font-semibold text-white">{{ $mov['descricao'] }}</span>
                                    <span class="block text-[10px] text-green-400 font-bold uppercase tracking-widest mt-0.5">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-right font-bold {{ $mov['tipo'] == 'receita' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $mov['tipo'] == 'receita' ? '+' : '-' }} R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-14 text-center text-gray-600">
                                <i class="fa-solid fa-receipt text-3xl mb-3 block text-gray-800"></i>
                                <p class="text-xs font-bold uppercase tracking-widest">Nenhum lançamento hoje.</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. Contas -->
    <div x-show="activeTab === 'contas'" x-cloak class="space-y-5">

        {{-- Sub-header --}}
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Gerencie receitas e despesas manuais</p>
            <button @click="showModal = true"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg shadow-green-900/20">
                <i class="fa-solid fa-plus text-xs"></i> Nova Conta
            </button>
        </div>

        {{-- Alertas de sucesso --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-900/20 border border-emerald-800/40 rounded-2xl px-5 py-3 text-emerald-400 text-xs font-bold">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Filtros --}}
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[180px]">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" id="contas-busca" placeholder="Pesquisar conta..."
                    class="block w-full pl-10 pr-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs font-medium text-gray-300 placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
            </div>
            <div class="flex items-center gap-2 bg-gray-900 border border-gray-800 px-3 py-2.5 rounded-xl">
                <i class="fa-solid fa-filter text-green-500 text-xs shrink-0"></i>
                <select id="contas-status"
                    class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-4 cursor-pointer">
                    <option value="">Todos os status</option>
                    <option value="pendente">Pendente</option>
                    <option value="pago">Pago</option>
                </select>
            </div>
            <div class="flex items-center gap-2 bg-gray-900 border border-gray-800 px-3 py-2.5 rounded-xl">
                <i class="fa-solid fa-layer-group text-green-500 text-xs shrink-0"></i>
                <select id="contas-categoria"
                    class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-4 cursor-pointer">
                    <option value="">Todas as categorias</option>
                    <option value="Aluguel">Aluguel</option>
                    <option value="Salários">Salários</option>
                    <option value="Produtos">Produtos</option>
                    <option value="Infraestrutura">Infraestrutura</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="contas-tabela">
                    <thead class="bg-gray-900/60 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-5 py-4">Descrição</th>
                            <th class="px-5 py-4">Categoria</th>
                            <th class="px-5 py-4 text-center">Vencimento</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-right">Valor</th>
                            <th class="px-5 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50" id="contas-body">
                        @forelse($contasLista as $conta)
                        <tr class="hover:bg-gray-900/50 transition-colors group contas-row"
                            data-descricao="{{ strtolower($conta->descricao) }}"
                            data-status="{{ $conta->status }}"
                            data-categoria="{{ strtolower($conta->categoria ?? '') }}">
                            <td class="px-5 py-4">
                                <div class="font-bold text-white text-sm">{{ $conta->descricao }}</div>
                                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ $conta->tipo }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-[10px] font-bold text-gray-400 bg-gray-800 px-3 py-1 rounded-lg border border-gray-700">
                                    {{ $conta->categoria ?? '—' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="text-xs font-bold {{ $conta->vencida ? 'text-rose-400' : 'text-gray-400' }}">
                                    {{ $conta->vencimento->format('d/m/Y') }}
                                </div>
                                @if($conta->pago_em)
                                    <div class="text-[9px] text-emerald-500 font-bold uppercase mt-0.5">
                                        Pago em {{ $conta->pago_em->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border
                                    {{ $conta->status === 'pago'
                                        ? 'bg-emerald-900/30 text-emerald-400 border-emerald-800/50'
                                        : ($conta->vencida
                                            ? 'bg-rose-900/30 text-rose-400 border-rose-800/50'
                                            : 'bg-amber-900/30 text-amber-400 border-amber-800/50') }}">
                                    {{ $conta->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="font-bold text-sm {{ $conta->tipo === 'receita' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $conta->tipo === 'receita' ? '+' : '−' }} R$ {{ number_format($conta->valor, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    @if($conta->status === 'pendente')
                                    <form action="{{ route('panel.contas.pagar', $conta->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 hover:text-emerald-300 transition-colors">
                                            Baixar
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('panel.contas.destroy', $conta->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Deseja excluir esta conta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-600 hover:text-rose-400 transition-colors">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-gray-800/50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                                    <i class="fa-solid fa-file-invoice-dollar text-2xl text-gray-600"></i>
                                </div>
                                <p class="text-sm font-bold text-white uppercase tracking-widest">Nenhuma conta cadastrada</p>
                                <p class="text-[10px] text-gray-500 mt-2 font-bold tracking-widest">Organize suas finanças cadastrando a primeira conta.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 6. Relatórios -->
    <div x-show="activeTab === 'relatorios'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-7">
            <h3 class="font-bold text-white mb-7 flex items-center gap-2 uppercase tracking-widest text-xs">
                <i class="fa-solid fa-heart-pulse text-green-500"></i> Saúde Financeira
            </h3>
            <div class="space-y-5">
                @foreach([['Ponto de Equilíbrio','R$ 2.450,00','text-white'],['Ticket Médio','R$ 45,00','text-white'],['Margem de Lucro','62%','text-emerald-400']] as [$label,$val,$cls])
                <div class="flex justify-between items-end border-b border-gray-800/60 pb-4">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-tight">{{ $label }}</span>
                    <span class="font-bold {{ $cls }} text-base">{{ $val }}</span>
                </div>
                @endforeach
            </div>
            <button class="w-full mt-8 py-3.5 rounded-xl border border-gray-700 text-[10px] font-bold text-gray-500 uppercase tracking-widest hover:bg-gray-800 hover:text-gray-300 transition-all">Definir Metas</button>
        </div>
        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-7">
            <h3 class="font-bold text-white mb-7 flex items-center gap-2 uppercase tracking-widest text-xs">
                <i class="fa-solid fa-file-export text-gray-400"></i> Exportação
            </h3>
            <div class="space-y-3">
                <button class="w-full flex items-center gap-4 p-4 rounded-xl border border-gray-800 hover:border-green-700/50 hover:bg-green-900/10 transition-all text-left group">
                    <div class="w-12 h-12 bg-green-900/30 text-green-400 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform"><i class="fa-solid fa-file-pdf text-lg"></i></div>
                    <div>
                        <p class="text-sm font-bold text-white">Faturamento Mensal</p>
                        <p class="text-[10px] text-gray-500 font-bold mt-0.5 uppercase tracking-tight">Relatório completo em PDF</p>
                    </div>
                </button>
                <button class="w-full flex items-center gap-4 p-4 rounded-xl border border-gray-800 hover:border-emerald-700/50 hover:bg-emerald-900/10 transition-all text-left group">
                    <div class="w-12 h-12 bg-emerald-900/30 text-emerald-400 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform"><i class="fa-solid fa-file-csv text-lg"></i></div>
                    <div>
                        <p class="text-sm font-bold text-white">Cálculo de Comissões</p>
                        <p class="text-[10px] text-gray-500 font-bold mt-0.5 uppercase tracking-tight">Exportar para Excel/CSV</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Nova Conta ────────────────────────────────────────── --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:p-0">

            <div x-show="showModal"
                x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm"
                @click="showModal = false"></div>

            <div x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                class="relative inline-block align-bottom bg-[#111827] border border-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-10">

                <form action="{{ route('panel.contas.store') }}" method="POST">
                    @csrf
                    <div class="px-6 pt-7 pb-5 sm:p-8">

                        {{-- Cabeçalho --}}
                        <div class="flex items-center justify-between mb-7">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-green-500/15 rounded-2xl flex items-center justify-center text-green-400 border border-green-800/30">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-white uppercase tracking-tight">Lançamento Financeiro</h3>
                                    <p class="text-[10px] text-gray-500 font-medium mt-0.5">Registre uma entrada ou saída.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false"
                                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-white hover:bg-gray-800 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            {{-- Descrição --}}
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Descrição <span class="text-rose-400">*</span></label>
                                <input type="text" name="descricao" required placeholder="Ex: Aluguel, Compra de Shampoos"
                                    class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Valor --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Valor (R$) <span class="text-rose-400">*</span></label>
                                    <input type="number" step="0.01" name="valor" required placeholder="0,00"
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                </div>
                                {{-- Tipo --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tipo <span class="text-rose-400">*</span></label>
                                    <select name="tipo" required
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                        <option value="despesa">Despesa (Saída)</option>
                                        <option value="receita">Receita (Entrada)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Vencimento --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Vencimento <span class="text-rose-400">*</span></label>
                                    <input type="date" name="vencimento" value="{{ date('Y-m-d') }}" required
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                </div>
                                {{-- Categoria --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Categoria</label>
                                    <select name="categoria"
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                        <option value="">Selecione...</option>
                                        <option value="Aluguel">Aluguel</option>
                                        <option value="Salários">Salários</option>
                                        <option value="Produtos">Produtos</option>
                                        <option value="Infraestrutura">Infraestrutura</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Recorrência --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Recorrência</label>
                                    <select name="recorrencia"
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                        <option value="nenhuma">Nenhuma</option>
                                        <option value="semanal">Semanal</option>
                                        <option value="mensal">Mensal</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>
                                {{-- Parcelas --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Qtd. Parcelas</label>
                                    <input type="number" name="total_parcelas" min="1" value="1"
                                        class="block w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-xl text-sm font-medium text-white focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rodapé --}}
                    <div class="px-6 pb-6 sm:px-8 flex flex-row-reverse gap-3">
                        <button type="submit"
                            class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg shadow-green-900/20">
                            Lançar Conta
                        </button>
                        <button type="button" @click="showModal = false"
                            class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
@php
    // Agrupamento diário de movimentações para o gráfico de fluxo de caixa
    $__movDiario   = $movimentacoes->groupBy(fn($m) => \Carbon\Carbon::parse($m['data'])->format('Y-m-d'));
    $__datas       = $__movDiario->keys()->sort()->values();
    $__fluxoLabels = $__datas->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values();
    $__fluxoRec    = $__datas->map(fn($d) => (float) $__movDiario->get($d)->where('tipo','receita')->sum('valor'))->values();
    $__fluxoDep    = $__datas->map(fn($d) => (float) $__movDiario->get($d)->where('tipo','despesa')->sum('valor'))->values();
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Dados PHP → JS ─────────────────────────────────────────────────────────
const _fluxoLabels   = {!! json_encode($__fluxoLabels->toArray()) !!};
const _fluxoRec      = {!! json_encode($__fluxoRec->toArray()) !!};
const _fluxoDep      = {!! json_encode($__fluxoDep->toArray()) !!};
const _distRec       = {{ $resumoMes['receitas'] }};
const _distDep       = {{ $resumoMes['despesas'] }};
const _evolLabels    = {!! json_encode($faturamentoDiario->map(fn($d) => \Carbon\Carbon::parse($d->data)->format('d/m'))->toArray()) !!};
const _evolData      = {!! json_encode($faturamentoDiario->pluck('total')->toArray()) !!};
const _svcLabels     = {!! json_encode($servicosDistribuiucao->pluck('descricao')->toArray()) !!};
const _svcData       = {!! json_encode($servicosDistribuiucao->pluck('qtd')->toArray()) !!};

// ── Registro de instâncias para evitar reinicialização ─────────────────────
window._glowCharts = {};

// ── Helpers de estilo ───────────────────────────────────────────────────────
const GRID     = 'rgba(255,255,255,0.05)';
const TICK     = { font: { size: 11, family: "'Outfit', sans-serif" }, color: '#6b7280' };
const TOOLTIP  = {
    backgroundColor: '#1f2937',
    titleColor: '#f3f4f6',
    bodyColor: '#9ca3af',
    borderColor: '#374151',
    borderWidth: 1,
    padding: 12,
    cornerRadius: 10,
    titleFont: { size: 11, weight: 'bold' },
    bodyFont:  { size: 12, weight: 'bold' },
};
const LEGEND_STYLE = {
    usePointStyle: true,
    pointStyle: 'circle',
    padding: 18,
    font: { size: 11, weight: '600', family: "'Outfit', sans-serif" },
    color: '#9ca3af',
};
const brl = v => 'R$ ' + Number(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

// ── Inicializador principal (chamado pelo Alpine após layout) ─────────────────
window.glowInitChart = function(tab) {
    // Destroy existing instances — handles Turbo navigation and cache-preview races
    ['fluxo', 'dist', 'evol', 'svc'].forEach(k => {
        if (window._glowCharts && window._glowCharts[k]) {
            try { window._glowCharts[k].destroy(); } catch(e) {}
            window._glowCharts[k] = null;
        }
    });

    if (typeof Chart === 'undefined') {
        setTimeout(() => window.glowInitChart(tab), 100);
        return;
    }

    if (tab === 'visao-geral') {
        initFluxoCaixa();
        initDistribuicao();
    } else if (tab === 'funcionarios') {
        initEvolucao();
        initServicos();
    }
};

// ── Fluxo de Caixa ──────────────────────────────────────────────────────────
function initFluxoCaixa() {
    const el = document.getElementById('chartFluxoCaixa');
    if (!el) return;

    const ctx = el.getContext('2d');
    const gradG = ctx.createLinearGradient(0, 0, 0, el.offsetHeight || 220);
    gradG.addColorStop(0, 'rgba(34,197,94,0.22)');
    gradG.addColorStop(1, 'rgba(34,197,94,0.00)');
    const gradR = ctx.createLinearGradient(0, 0, 0, el.offsetHeight || 220);
    gradR.addColorStop(0, 'rgba(244,63,94,0.18)');
    gradR.addColorStop(1, 'rgba(244,63,94,0.00)');

    const labels = _fluxoLabels.length ? _fluxoLabels : ['—'];
    window._glowCharts.fluxo = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Receitas',
                    data: _fluxoRec,
                    borderColor: '#22c55e',
                    backgroundColor: gradG,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.42,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#111827',
                    pointBorderWidth: 2.5,
                },
                {
                    label: 'Despesas',
                    data: _fluxoDep,
                    borderColor: '#f43f5e',
                    backgroundColor: gradR,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.42,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#f43f5e',
                    pointBorderColor: '#111827',
                    pointBorderWidth: 2.5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 600, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: true, position: 'top', labels: LEGEND_STYLE },
                tooltip: { ...TOOLTIP, callbacks: { label: c => ' ' + brl(c.parsed.y) } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: GRID, drawBorder: false },
                    border: { dash: [4, 4], display: false },
                    ticks: { ...TICK, callback: v => 'R$ ' + Number(v).toLocaleString('pt-BR') }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: TICK
                }
            }
        }
    });
}

// ── Distribuição (Donut) ────────────────────────────────────────────────────
function initDistribuicao() {
    const el = document.getElementById('chartDistribuicao');
    if (!el) return;

    const hasData = _distRec + _distDep > 0;
    window._glowCharts.dist = new Chart(el.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Receitas', 'Despesas'],
            datasets: [{
                data: hasData ? [_distRec, _distDep] : [1, 0],
                backgroundColor: ['rgba(34,197,94,0.90)', 'rgba(244,63,94,0.90)'],
                borderColor: ['#16a34a33', '#e11d4833'],
                borderWidth: hasData ? 0 : 2,
                hoverOffset: 8,
                borderRadius: 6,
                spacing: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { position: 'bottom', labels: LEGEND_STYLE },
                tooltip: { ...TOOLTIP, callbacks: { label: c => ' ' + brl(c.parsed) } }
            },
            cutout: '70%',
        }
    });
}

// ── Evolução de Receita (Equipe) ────────────────────────────────────────────
function initEvolucao() {
    const el = document.getElementById('chartEvolucao');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, el.offsetHeight || 260);
    grad.addColorStop(0, 'rgba(34,197,94,0.25)');
    grad.addColorStop(1, 'rgba(34,197,94,0.00)');

    window._glowCharts.evol = new Chart(ctx, {
        type: 'line',
        data: {
            labels: _evolLabels.length ? _evolLabels : ['—'],
            datasets: [{
                label: 'Faturamento',
                data: _evolData,
                borderColor: '#22c55e',
                backgroundColor: grad,
                borderWidth: 2.5,
                fill: true,
                tension: 0.42,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointBackgroundColor: '#22c55e',
                pointBorderColor: '#111827',
                pointBorderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 600, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: { ...TOOLTIP, callbacks: { label: c => ' ' + brl(c.parsed.y) } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: GRID, drawBorder: false },
                    border: { display: false },
                    ticks: { ...TICK, callback: v => 'R$ ' + Number(v).toLocaleString('pt-BR') }
                },
                x: { grid: { display: false }, border: { display: false }, ticks: TICK }
            }
        }
    });
}

// ── Serviços Principais (Donut – Equipe) ────────────────────────────────────
function initServicos() {
    const el = document.getElementById('chartServicos');
    if (!el) return;

    const COLORS = ['#22c55e','#16a34a','#4ade80','#86efac','#bbf7d0'];
    window._glowCharts.svc = new Chart(el.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: _svcLabels,
            datasets: [{
                data: _svcData,
                backgroundColor: COLORS,
                borderColor: '#111827',
                borderWidth: 3,
                hoverOffset: 6,
                borderRadius: 4,
                spacing: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { ...LEGEND_STYLE, padding: 12 }
                },
                tooltip: TOOLTIP,
            },
            cutout: '72%'
        }
    });
}

// ── Filtro client-side da tabela de contas ──────────────────────────────────
(function() {
    function filtrarContas() {
        const busca     = (document.getElementById('contas-busca')?.value || '').toLowerCase();
        const status    = (document.getElementById('contas-status')?.value || '').toLowerCase();
        const categoria = (document.getElementById('contas-categoria')?.value || '').toLowerCase();
        document.querySelectorAll('#contas-body .contas-row').forEach(row => {
            const ok = (!busca     || row.dataset.descricao.includes(busca))
                    && (!status    || row.dataset.status === status)
                    && (!categoria || row.dataset.categoria.includes(categoria));
            row.style.display = ok ? '' : 'none';
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        ['contas-busca','contas-status','contas-categoria'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener(id === 'contas-busca' ? 'input' : 'change', filtrarContas);
        });
    });
})();
</script>
@endpush
