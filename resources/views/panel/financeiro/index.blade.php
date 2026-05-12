@extends('layouts.app')
@section('title', 'Financeiro')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: '{{ request('tab', 'visao-geral') }}',
    periodo: '{{ request('periodo', 'mes') }}'
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Financeiro</h1>
            <p class="text-sm text-gray-500 mt-1">Gestão completa das movimentações do seu estabelecimento.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 p-1 bg-gray-800 rounded-2xl w-fit overflow-x-auto">
        <button @click="activeTab = 'visao-geral'" :class="activeTab === 'visao-geral' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">Visão Geral</button>
        <button @click="activeTab = 'movimentacoes'" :class="activeTab === 'movimentacoes' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">Extrato</button>
        <button @click="activeTab = 'funcionarios'" :class="activeTab === 'funcionarios' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">Equipe</button>
        <button @click="activeTab = 'caixa'" :class="activeTab === 'caixa' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">Caixa</button>
        <button @click="activeTab = 'relatorios'" :class="activeTab === 'relatorios' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">Relatórios</button>
    </div>

    <!-- Tab Content -->
    
    <!-- 1. Visão Geral -->
    <div x-show="activeTab === 'visao-geral'" x-cloak x-transition:enter class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Receitas do Mês</p>
                <p class="text-3xl font-bold text-emerald-600 mt-2">R$ {{ number_format($resumoMes['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border-l-4 border-l-rose-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Despesas do Mês</p>
                <p class="text-3xl font-bold text-rose-600 mt-2">R$ {{ number_format($resumoMes['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-violet-900 rounded-[24px] p-6 shadow-xl shadow-violet-900/20 text-white">
                <p class="text-[10px] text-violet-300 font-bold uppercase tracking-widest">Lucro Estimado</p>
                <p class="text-3xl font-bold mt-2">R$ {{ number_format($resumoMes['lucro'], 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Fluxo de Caixa -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <h2 class="text-base font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tight">
                    <i class="fa-solid fa-chart-line text-violet-500"></i> Fluxo de Caixa
                </h2>
                <div class="h-64 flex items-center justify-center text-gray-400 bg-gray-800/50 rounded-[24px] border border-dashed border-gray-700">
                    <div class="text-center">
                        <i class="fa-solid fa-chart-area text-3xl mb-3 text-gray-200"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">Análise em tempo real</p>
                        <p class="text-[10px] text-gray-400 mt-1">Consolidando dados financeiros...</p>
                    </div>
                </div>
            </div>

            <!-- Faturamento por Profissional -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-bold text-white flex items-center gap-2 uppercase tracking-tight">
                        <i class="fa-solid fa-users text-violet-500"></i> Desempenho da Equipe
                    </h2>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mês Atual</span>
                </div>

                <div class="space-y-6">
                    @forelse($faturamentoPorProfissional as $fat)
                        @php
                            $porcentagem = $totalEntradas > 0 ? ($fat['total'] / $totalEntradas) * 100 : 0;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-bold text-gray-300">{{ $fat['nome'] }}</span>
                                <span class="text-sm font-bold text-white">R$ {{ number_format($fat['total'], 2, ',', '.') }}</span>
                            </div>
                            <div class="h-2 w-full bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-violet-600 rounded-full" style="width: {{ $porcentagem }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                <span>Participação no faturamento</span>
                                <span>{{ number_format($porcentagem, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                            <i class="fa-solid fa-user-slash text-3xl mb-2 text-gray-100"></i>
                            <p class="text-xs font-medium">Nenhum dado disponível.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Movimentações -->
    <div x-show="activeTab === 'movimentacoes'" x-cloak x-transition:enter class="space-y-6">
        <!-- Filtros Extrato -->
        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
            <form action="{{ route('panel.financeiro.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <input type="hidden" name="tab" value="movimentacoes">
                <div class="flex-1 space-y-2 w-full sm:w-auto">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Filtro de Período</label>
                    <select name="periodo" x-model="periodo" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50 font-medium">
                        <option value="mes">Este Mês</option>
                        <option value="hoje">Hoje</option>
                        <option value="semana">Esta Semana</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                
                <div x-show="periodo === 'personalizado'" class="flex gap-2 flex-1 w-full sm:w-auto">
                    <div class="flex-1 space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">De</label>
                        <input type="date" name="de" value="{{ request('de') }}" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50">
                    </div>
                    <div class="flex-1 space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Até</label>
                        <input type="date" name="ate" value="{{ request('ate') }}" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50">
                    </div>
                </div>

                <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-all w-full sm:w-auto shadow-lg shadow-gray-100">
                    Aplicar Filtros
                </button>
            </form>
        </div>

        <!-- Tabela Extrato -->
        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800/50 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-5">Data/Hora</th>
                            <th class="px-6 py-5">Lançamento</th>
                            <th class="px-6 py-5">Categoria</th>
                            <th class="px-6 py-5 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($movimentacoes as $mov)
                            <tr class="hover:bg-gray-900/80 transition-colors">
                                <td class="px-6 py-4 text-gray-400 font-medium text-xs">{{ $mov['data']->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $mov['descricao'] }}</div>
                                    <div class="text-[10px] text-violet-500 font-bold uppercase tracking-tighter mt-0.5">{{ $mov['forma_pagamento'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-800 text-gray-400 rounded-lg text-[9px] font-bold uppercase tracking-widest">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold {{ $mov['tipo'] == 'receita' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $mov['tipo'] == 'receita' ? '+' : '-' }} R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-gray-400">
                                    <i class="fa-solid fa-receipt text-5xl mb-4 text-gray-100"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">Nenhuma movimentação</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Análise por Funcionário -->
    <div x-show="activeTab === 'funcionarios'" x-cloak x-transition:enter class="space-y-6">
        <!-- Filtros -->
        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
            <form action="{{ route('panel.financeiro.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                <input type="hidden" name="tab" value="funcionarios">
                <div class="flex-1 space-y-2 w-full lg:w-auto">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Selecionar Especialista</label>
                    <select name="profissional_id" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50 font-bold">
                        <option value="">Equipe Completa</option>
                        @foreach($profissionais as $prof)
                            <option value="{{ $prof->id }}" {{ request('profissional_id') == $prof->id ? 'selected' : '' }}>{{ $prof->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 space-y-2 w-full lg:w-auto">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Período</label>
                    <select name="periodo" x-model="periodo" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50 font-bold">
                        <option value="mes">Este Mês</option>
                        <option value="hoje">Hoje</option>
                        <option value="semana">Esta Semana</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                
                <div x-show="periodo === 'personalizado'" class="flex gap-2 flex-1 w-full lg:w-auto">
                    <div class="flex-1 space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">De</label>
                        <input type="date" name="de" value="{{ request('de') }}" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50">
                    </div>
                    <div class="flex-1 space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Até</label>
                        <input type="date" name="ate" value="{{ request('ate') }}" class="block w-full px-4 py-3 border border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-violet-500 outline-none bg-gray-800/50">
                    </div>
                </div>

                <button type="submit" class="bg-violet-600 text-white px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-violet-700 transition-all w-full lg:w-auto shadow-lg shadow-violet-900/20">
                    Atualizar Relatório
                </button>
            </form>
        </div>

        <!-- Métricas Rápidas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Faturamento Gerado</p>
                <p class="text-3xl font-bold text-white mt-2">R$ {{ number_format($faturamentoDiario->sum('total'), 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Serviços Executados</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $servicosDistribuiucao->sum('qtd') }}</p>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ticket Médio</p>
                @php $qtdTotal = $servicosDistribuiucao->sum('qtd'); @endphp
                <p class="text-3xl font-bold text-white mt-2">R$ {{ $qtdTotal > 0 ? number_format($faturamentoDiario->sum('total') / $qtdTotal, 2, ',', '.') : '0,00' }}</p>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Evolução de Faturamento -->
            <div class="lg:col-span-2 bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <h3 class="text-sm font-bold text-white uppercase mb-8 flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-chart-line text-violet-500"></i> Evolução de Receita
                </h3>
                <div class="h-80">
                    <canvas id="chartEvolucao"></canvas>
                </div>
            </div>

            <!-- Mix de Serviços -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <h3 class="text-sm font-bold text-white uppercase mb-8 flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-magic-wand-sparkles text-amber-500"></i> Principais Serviços
                </h3>
                <div class="h-64 mb-8">
                    <canvas id="chartServicos"></canvas>
                </div>
                <div class="space-y-4">
                    @foreach($servicosDistribuiucao as $serv)
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase">
                            <span class="text-gray-400 truncate w-32 tracking-tighter">{{ $serv->descricao }}</span>
                            <span class="text-white bg-gray-800/50 px-2 py-0.5 rounded-md">{{ $serv->qtd }} un.</span>
                            <span class="text-violet-600">R$ {{ number_format($serv->total, 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gráfico de Evolução
                const ctxEvolucao = document.getElementById('chartEvolucao').getContext('2d');
                new Chart(ctxEvolucao, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($faturamentoDiario->map(fn($d) => \Carbon\Carbon::parse($d->data)->format('d/m'))) !!},
                        datasets: [{
                            label: 'Faturamento R$',
                            data: {!! json_encode($faturamentoDiario->pluck('total')) !!},
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.05)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#7c3aed',
                            pointBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f9fafb' },
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#9ca3af' }
                            }
                        }
                    }
                });

                // Gráfico de Serviços
                const ctxServicos = document.getElementById('chartServicos').getContext('2d');
                new Chart(ctxServicos, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($servicosDistribuiucao->pluck('descricao')) !!},
                        datasets: [{
                            data: {!! json_encode($servicosDistribuiucao->pluck('qtd')) !!},
                            backgroundColor: [
                                '#7c3aed', '#a855f7', '#c084fc', '#d8b4fe', '#f3e8ff'
                            ],
                            borderWidth: 4,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { size: 10, weight: 'bold' },
                                    color: '#6b7280'
                                }
                            }
                        },
                        cutout: '75%'
                    }
                });
            });
        </script>
    </div>

    <!-- 4. Gestão de Caixa -->
    <div x-show="activeTab === 'caixa'" x-cloak x-transition:enter class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 uppercase tracking-tight">
                <i class="fa-solid fa-cash-register text-gray-400"></i> Fluxo do Dia
            </h2>
            <div class="flex gap-2">
                <button class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm text-gray-300 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800/50 shadow-sm flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-arrow-up text-emerald-500"></i> Entrada
                </button>
                <button class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm text-gray-300 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800/50 shadow-sm flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-arrow-down text-rose-500"></i> Saída
                </button>
                <button class="btn-premium text-white px-6 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-violet-900/20 transition-all">Fechar Caixa</button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Saldo Atual</p>
                <p class="text-3xl font-bold text-white mt-2">R$ {{ number_format($resumoHoje['lucro'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">Entradas</p>
                <p class="text-3xl font-bold text-emerald-600 mt-2">R$ {{ number_format($resumoHoje['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm">
                <p class="text-[10px] text-rose-600 font-bold uppercase tracking-widest">Saídas</p>
                <p class="text-3xl font-bold text-rose-600 mt-2">R$ {{ number_format($resumoHoje['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-violet-900 rounded-[24px] p-6 shadow-xl shadow-violet-900/20 text-white">
                <p class="text-[10px] text-violet-300 font-bold uppercase tracking-widest">Status do Caixa</p>
                <p class="text-xl font-bold mt-2 flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span> Aberto
                </p>
            </div>
        </div>

        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-900/30">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-list-ul"></i> Movimentações de Hoje
                </h3>
                <span class="text-[9px] font-bold text-gray-400 bg-gray-900/50 px-3 py-1.5 rounded-full border border-gray-800 uppercase tracking-widest">{{ now()->format('d M, Y') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <tbody class="divide-y divide-gray-50">
                        @php $hojeMov = $movimentacoes->filter(fn($m) => $m['data']->isToday()); @endphp
                        @forelse($hojeMov as $mov)
                            <tr class="hover:bg-gray-900/80 transition-colors">
                                <td class="px-6 py-5 text-gray-400 font-bold text-xs w-24">{{ $mov['data']->format('H:i') }}</td>
                                <td class="px-6 py-5">
                                    <span class="font-bold text-white text-base">{{ $mov['descricao'] }}</span>
                                    <span class="block text-[10px] text-violet-500 font-bold uppercase tracking-widest mt-1">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-6 py-5 text-right font-bold text-base {{ $mov['tipo'] == 'receita' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $mov['tipo'] == 'receita' ? '+' : '-' }} R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-20 text-center text-gray-400">
                                    <i class="fa-solid fa-receipt text-5xl mb-4 text-gray-100"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">Nenhum lançamento hoje.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Relatórios -->
    <div x-show="activeTab === 'relatorios'" x-cloak x-transition:enter class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-8 shadow-sm">
            <h3 class="font-bold text-white mb-8 flex items-center gap-2 uppercase tracking-widest text-xs">
                <i class="fa-solid fa-heart-pulse text-violet-500"></i> Saúde Financeira
            </h3>
            <div class="space-y-6">
                <div class="flex justify-between items-end border-b border-gray-50 pb-3">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Ponto de Equilíbrio</span>
                    <span class="font-bold text-white text-base">R$ 2.450,00</span>
                </div>
                <div class="flex justify-between items-end border-b border-gray-50 pb-3">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Ticket Médio</span>
                    <span class="font-bold text-white text-base">R$ 45,00</span>
                </div>
                <div class="flex justify-between items-end border-b border-gray-50 pb-3">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Margem de Lucro</span>
                    <span class="font-bold text-emerald-600 text-base">62%</span>
                </div>
            </div>
            <button class="w-full mt-10 py-4 rounded-2xl border border-gray-800 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] hover:bg-gray-800/50 hover:text-gray-400 transition-all">Definir Metas</button>
        </div>

        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-8 shadow-sm">
            <h3 class="font-bold text-white mb-8 flex items-center gap-2 uppercase tracking-widest text-xs">
                <i class="fa-solid fa-file-export text-gray-400"></i> Exportação Inteligente
            </h3>
            <div class="grid grid-cols-1 gap-4">
                <button class="flex items-center gap-5 p-5 rounded-3xl border border-gray-50 hover:border-violet-800 hover:bg-violet-50/30 transition-all text-left group">
                    <div class="w-14 h-14 bg-violet-900/30 text-violet-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><i class="fa-solid fa-file-pdf text-xl"></i></div>
                    <div>
                        <p class="text-sm font-bold text-white uppercase tracking-tight">Faturamento Mensal</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Relatório completo em PDF</p>
                    </div>
                </button>
                <button class="flex items-center gap-5 p-5 rounded-3xl border border-gray-50 hover:border-emerald-800 hover:bg-emerald-50/30 transition-all text-left group">
                    <div class="w-14 h-14 bg-emerald-900/30 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"><i class="fa-solid fa-file-csv text-xl"></i></div>
                    <div>
                        <p class="text-sm font-bold text-white uppercase tracking-tight">Cálculo de Comissões</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Exportar para Excel/CSV</p>
                    </div>
                </button>
            </div>
        </div>
    </div>             </button>
            </div>
        </div>
    </div>

</div>
@endsection
