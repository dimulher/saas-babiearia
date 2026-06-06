@extends('layouts.app')
@section('title', 'Financeiro')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: '{{ request('tab', 'visao-geral') }}',
    periodo: '{{ request('periodo', 'mes') }}'
}">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Financeiro</h1>
            <p class="text-sm text-gray-500 mt-1">Gestão completa das movimentações do seu estabelecimento.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 p-1 bg-gray-900 rounded-xl w-fit overflow-x-auto border border-gray-800">
        @foreach([['visao-geral','Visão Geral'],['movimentacoes','Extrato'],['funcionarios','Equipe'],['caixa','Caixa'],['relatorios','Relatórios']] as [$tab, $label])
        <button @click="activeTab = '{{ $tab }}'" :class="activeTab === '{{ $tab }}' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'"
            class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">{{ $label }}</button>
        @endforeach
    </div>

    <!-- 1. Visão Geral -->
    <div x-show="activeTab === 'visao-geral'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6 border-l-4 border-l-emerald-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Receitas do Mês</p>
                <p class="text-3xl font-bold text-emerald-400 mt-2">R$ {{ number_format($resumoMes['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6 border-l-4 border-l-rose-500">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Despesas do Mês</p>
                <p class="text-3xl font-bold text-rose-400 mt-2">R$ {{ number_format($resumoMes['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-6 shadow-xl shadow-green-900/30 text-white">
                <p class="text-[10px] text-green-200 font-bold uppercase tracking-widest">Lucro Estimado</p>
                <p class="text-3xl font-bold mt-2">R$ {{ number_format($resumoMes['lucro'], 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <h2 class="text-sm font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tight">
                    <i class="fa-solid fa-chart-line text-green-500"></i> Fluxo de Caixa
                </h2>
                <div class="h-64 flex items-center justify-center text-gray-400 bg-gray-900/50 rounded-xl border border-dashed border-gray-800">
                    <div class="text-center">
                        <i class="fa-solid fa-chart-area text-3xl mb-3 text-gray-700"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">Análise em tempo real</p>
                        <p class="text-[10px] text-gray-500 mt-1">Consolidando dados financeiros...</p>
                    </div>
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
                            <th class="px-6 py-4">Data/Hora</th>
                            <th class="px-6 py-4">Lançamento</th>
                            <th class="px-6 py-4">Categoria</th>
                            <th class="px-6 py-4 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($movimentacoes as $mov)
                            <tr class="hover:bg-gray-900/40 transition-colors">
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $mov['data']->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white text-sm">{{ $mov['descricao'] }}</div>
                                    <div class="text-[10px] text-green-400 font-bold uppercase tracking-tight mt-0.5">{{ $mov['forma_pagamento'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-800 text-gray-400 rounded-lg text-[9px] font-bold uppercase tracking-widest">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold {{ $mov['tipo'] == 'receita' ? 'text-emerald-400' : 'text-rose-400' }}">
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

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Faturamento</p>
                <p class="text-3xl font-bold text-white mt-2">R$ {{ number_format($faturamentoDiario->sum('total'), 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Serviços</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $servicosDistribuiucao->sum('qtd') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
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
                <div class="h-72"><canvas id="chartEvolucao"></canvas></div>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white uppercase mb-6 flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-magic-wand-sparkles text-amber-400"></i> Principais Serviços
                </h3>
                <div class="h-56 mb-6"><canvas id="chartServicos"></canvas></div>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctxEvolucao = document.getElementById('chartEvolucao').getContext('2d');
                new Chart(ctxEvolucao, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($faturamentoDiario->map(fn($d) => \Carbon\Carbon::parse($d->data)->format('d/m'))) !!},
                        datasets: [{
                            label: 'Faturamento R$',
                            data: {!! json_encode($faturamentoDiario->pluck('total')) !!},
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#0B0F19',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { font: { size: 10 }, color: '#6b7280' } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#6b7280' } }
                        }
                    }
                });
                const ctxServicos = document.getElementById('chartServicos').getContext('2d');
                new Chart(ctxServicos, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($servicosDistribuiucao->pluck('descricao')) !!},
                        datasets: [{
                            data: {!! json_encode($servicosDistribuiucao->pluck('qtd')) !!},
                            backgroundColor: ['#22c55e','#16a34a','#15803d','#166534','#14532d'],
                            borderWidth: 3,
                            borderColor: '#111827'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, font: { size: 10 }, color: '#6b7280' } } },
                        cutout: '72%'
                    }
                });
            });
        </script>
    </div>

    <!-- 4. Caixa -->
    <div x-show="activeTab === 'caixa'" x-cloak class="space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 uppercase tracking-tight">
                <i class="fa-solid fa-cash-register text-gray-400"></i> Fluxo do Dia
            </h2>
            <div class="flex gap-2">
                <button class="bg-[#111827] border border-gray-800 text-gray-300 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 flex items-center gap-2 transition-all rounded-xl">
                    <i class="fa-solid fa-arrow-up text-emerald-400"></i> Entrada
                </button>
                <button class="bg-[#111827] border border-gray-800 text-gray-300 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 flex items-center gap-2 transition-all rounded-xl">
                    <i class="fa-solid fa-arrow-down text-rose-400"></i> Saída
                </button>
                <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">Fechar Caixa</button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Saldo Atual</p>
                <p class="text-3xl font-bold text-white mt-2">R$ {{ number_format($resumoHoje['lucro'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">Entradas</p>
                <p class="text-3xl font-bold text-emerald-400 mt-2">R$ {{ number_format($resumoHoje['receitas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-rose-400 font-bold uppercase tracking-widest">Saídas</p>
                <p class="text-3xl font-bold text-rose-400 mt-2">R$ {{ number_format($resumoHoje['despesas'], 2, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-6 shadow-xl text-white">
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
                                <td class="px-6 py-4 text-gray-400 font-bold text-xs w-20">{{ $mov['data']->format('H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-white">{{ $mov['descricao'] }}</span>
                                    <span class="block text-[10px] text-green-400 font-bold uppercase tracking-widest mt-0.5">{{ $mov['categoria'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold {{ $mov['tipo'] == 'receita' ? 'text-emerald-400' : 'text-rose-400' }}">
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

    <!-- 5. Relatórios -->
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

</div>
@endsection
