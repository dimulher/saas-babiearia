@extends('layouts.app')
@section('title', 'Relatórios')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'servicos' }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Inteligência de Negócio</h1>
            <p class="text-sm text-gray-400 font-medium">Insights detalhados e performance do seu estabelecimento.</p>
        </div>
        <div class="bg-gray-900 px-4 py-2 rounded-xl border border-gray-800 flex items-center gap-3">
            <form action="{{ route('panel.relatorios.index') }}" method="GET" class="flex items-center gap-3">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Período:</span>
                <input type="month" name="mes" value="{{ $mes }}" class="text-xs font-bold border-none focus:ring-0 outline-none p-0 text-green-400 bg-transparent uppercase">
                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-green-900/30 text-green-400 hover:bg-green-500 hover:text-white transition-all">
                    <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-1 p-1 bg-gray-900 rounded-xl border border-gray-800 w-max min-w-full">
            @foreach([['servicos','fa-sparkles','Procedimentos'],['clientes','fa-users','Fidelização'],['profissionais','fa-user-tie','Performance Equipe']] as [$tab,$icon,$label])
            <button @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-5 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                <i class="fa-solid {{ $icon }} mr-1.5"></i> {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- 1. Serviços -->
    <div x-show="activeTab === 'servicos'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-900/20 rounded-full -mr-10 -mt-10"></div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 relative">Volume de Produção</p>
                <div class="flex items-baseline gap-2 relative">
                    <p class="text-4xl font-bold text-white tracking-tight">{{ $servicosPerformance->sum('qtd') }}</p>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">serviços</span>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-900/20 rounded-full -mr-10 -mt-10"></div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 relative">Receita Bruta</p>
                <div class="flex items-baseline gap-2 relative">
                    <span class="text-lg font-bold text-emerald-400 tracking-tight">R$</span>
                    <p class="text-4xl font-bold text-white tracking-tight">{{ number_format($totalReceitaServicos, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-6 shadow-xl text-white relative overflow-hidden">
                <p class="text-[10px] text-green-200 font-bold uppercase tracking-widest mb-2">Bestseller do Mês</p>
                <p class="text-xl font-bold uppercase tracking-tight truncate leading-none">{{ $servicoMaisVendido->nome ?? '—' }}</p>
                <p class="text-[10px] text-green-200 font-bold mt-2 uppercase tracking-widest">Líder em conversão</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-800/50 bg-gray-900/30">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-green-500"></i> Ranking de Procedimentos
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Procedimento</th>
                            <th class="px-6 py-4 text-center">Saídas</th>
                            <th class="px-6 py-4 text-right">Faturamento</th>
                            <th class="px-6 py-4 text-right">Share (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($servicosPerformance as $serv)
                            <tr class="hover:bg-gray-900/40 transition-colors group">
                                <td class="px-6 py-4 font-semibold text-white group-hover:text-green-400 transition-colors text-sm">{{ $serv->nome }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-gray-800 rounded-lg text-xs font-bold">{{ $serv->qtd }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-white">R$ {{ number_format($serv->receita, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="w-20 h-1.5 bg-gray-800 rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $totalReceitaServicos > 0 ? ($serv->receita / $totalReceitaServicos) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-green-400">
                                            {{ $totalReceitaServicos > 0 ? number_format(($serv->receita / $totalReceitaServicos) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                <i class="fa-solid fa-magnifying-glass-chart text-3xl mb-3 text-gray-700 block"></i>
                                <p class="text-sm font-bold text-white">Sem registros no período</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. Clientes -->
    <div x-show="activeTab === 'clientes'" x-cloak class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Novos (Mês)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold text-white tracking-tight">+{{ $clientesNovos }}</p>
                    <span class="text-[9px] font-bold text-emerald-400 uppercase tracking-widest">crescimento</span>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Carteira Ativa</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold text-white tracking-tight">{{ $clientesAtivos }}</p>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">clientes</span>
                </div>
            </div>
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Retenção Média</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold text-white tracking-tight">
                        {{ $clientesAtivos > 0 ? number_format($servicosPerformance->sum('qtd') / $clientesAtivos, 1) : 0 }}
                    </p>
                    <span class="text-[10px] font-bold text-green-400 uppercase tracking-widest">visitas / mês</span>
                </div>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-800/50 bg-gray-900/30 flex items-center justify-between">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-400"></i> Top 10 Clientes VIP (LTV)
                </h3>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-800 px-3 py-1 rounded-full border border-gray-700">Vitalício</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Perfil do Cliente</th>
                            <th class="px-6 py-4 text-center">Frequência</th>
                            <th class="px-6 py-4 text-right">LTV (Gasto Total)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($clientesRanking as $cli)
                            <tr class="hover:bg-gray-900/40 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-green-900/30 flex items-center justify-center text-xs font-bold text-green-400 border border-green-800/50 group-hover:bg-green-500 group-hover:text-white transition-all">
                                            {{ substr($cli->nome, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-white group-hover:text-green-400 transition-colors text-sm">{{ $cli->nome }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-900/30 text-green-400 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-green-800/50">
                                        {{ $cli->visitas }} Visitas
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-400 text-base">R$ {{ number_format($cli->gasto_total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-16 text-center text-gray-500">
                                <i class="fa-solid fa-users text-3xl mb-3 text-gray-700 block"></i>
                                <p class="text-sm font-bold text-white">Nenhum dado VIP disponível</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Profissionais -->
    <div x-show="activeTab === 'profissionais'" x-cloak class="space-y-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($profissionaisPerformance as $prof)
                <div class="bg-[#111827] border border-gray-800 hover:border-green-700/50 rounded-2xl p-7 flex flex-col sm:flex-row sm:items-center gap-6 transition-all group">
                    <div class="w-16 h-16 bg-green-900/30 rounded-2xl flex items-center justify-center text-2xl font-bold text-green-400 border border-green-800/50 shrink-0 group-hover:bg-green-500 group-hover:text-white transition-all">
                        {{ substr($prof->nome, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-white group-hover:text-green-400 transition-colors">{{ $prof->nome }}</h3>
                        <div class="mt-4 grid grid-cols-2 gap-5">
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Atendimentos</p>
                                <p class="text-2xl font-bold text-white tracking-tight">{{ $prof->atendimentos }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Gerado</p>
                                <p class="text-2xl font-bold text-emerald-400 tracking-tight">R$ {{ number_format($prof->receita_gerada, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0 flex flex-col items-end gap-2">
                        <div class="text-[10px] font-bold text-green-400 bg-green-900/30 border border-green-800/50 px-3 py-1.5 rounded-xl uppercase tracking-tight">
                            {{ $totalReceitaServicos > 0 ? number_format(($prof->receita_gerada / $totalReceitaServicos) * 100, 0) : 0 }}% do Salão
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-[#111827] border border-gray-800 rounded-2xl py-20 text-center">
                    <i class="fa-solid fa-user-group text-3xl text-gray-700 mb-3 block"></i>
                    <p class="text-sm font-bold text-white">Sem performance registrada</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
