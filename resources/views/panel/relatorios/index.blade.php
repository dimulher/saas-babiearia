@extends('layouts.app')
@section('title', 'Inteligência de Negócio')

@section('content')
<div class="space-y-8" x-data="{ 
    activeTab: 'servicos'
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Inteligência de Negócio</h1>
            <p class="text-sm text-gray-400 font-medium">Insights detalhados e performance do seu estabelecimento.</p>
        </div>
        <div class="bg-gray-900/50 p-1.5 px-4 rounded-2xl border border-gray-800 shadow-sm flex items-center gap-4">
            <form action="{{ route('panel.relatorios.index') }}" method="GET" class="flex items-center gap-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Período:</span>
                <input type="month" name="mes" value="{{ $mes }}" class="text-xs font-bold border-none focus:ring-0 outline-none p-0 text-violet-600 bg-transparent uppercase">
                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-violet-900/30 text-violet-600 hover:bg-violet-600 hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 p-1 bg-gray-800 rounded-[20px] w-fit overflow-x-auto no-scrollbar border border-gray-700/50">
        <button @click="activeTab = 'servicos'" 
            :class="activeTab === 'servicos' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" 
            class="px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
            <i class="fa-solid fa-sparkles mr-2"></i> Procedimentos
        </button>
        <button @click="activeTab = 'clientes'" 
            :class="activeTab === 'clientes' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" 
            class="px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
            <i class="fa-solid fa-users mr-2"></i> Fidelização
        </button>
        <button @click="activeTab = 'profissionais'" 
            :class="activeTab === 'profissionais' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'" 
            class="px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
            <i class="fa-solid fa-user-tie mr-2"></i> Performance Equipe
        </button>
    </div>

    <!-- Tab Content -->

    <!-- 1. Serviços -->
    <div x-show="activeTab === 'servicos'" x-cloak x-transition:enter class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border border-gray-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-violet-900/30 rounded-full -mr-12 -mt-12 opacity-50"></div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 relative">Volume de Produção</p>
                <div class="flex items-baseline gap-2 relative">
                    <p class="text-4xl font-bold text-white tracking-tighter italic">{{ $servicosPerformance->sum('qtd') }}</p>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">serviços</span>
                </div>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border border-gray-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-900/30 rounded-full -mr-12 -mt-12 opacity-50"></div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 relative">Receita Bruta (Serv)</p>
                <div class="flex items-baseline gap-2 relative">
                    <span class="text-lg font-bold text-emerald-500 tracking-tighter">R$</span>
                    <p class="text-4xl font-bold text-white tracking-tighter italic">{{ number_format($totalReceitaServicos, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-violet-600 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-xl text-white relative overflow-hidden group border-none">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-900/50 rounded-full -mr-16 -mt-16 opacity-10 blur-2xl group-hover:scale-125 transition-transform"></div>
                <p class="text-[10px] text-violet-200 font-bold uppercase tracking-widest mb-2 relative">Bestseller do Mês</p>
                <p class="text-xl font-bold uppercase tracking-tight truncate relative leading-none">{{ $servicoMaisVendido->nome ?? 'â€”' }}</p>
                <p class="text-[10px] text-violet-200 font-bold mt-2 opacity-80 uppercase tracking-widest">Líder em conversão</p>
            </div>
        </div>

        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800">
            <div class="p-6 border-b border-gray-50 bg-gray-900/80">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-3">
                    <i class="fa-solid fa-chart-line text-violet-500"></i> Ranking de Procedimentos
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800/50 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-5">Nome do Procedimento</th>
                            <th class="px-6 py-5 text-center">Saídas</th>
                            <th class="px-6 py-5 text-right">Faturamento</th>
                            <th class="px-6 py-5 text-right">Share (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($servicosPerformance as $serv)
                            <tr class="hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-5 font-bold text-white group-hover:text-violet-600 transition-colors">{{ $serv->nome }}</td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 bg-gray-800 rounded-lg text-xs font-bold italic">{{ $serv->qtd }}</span>
                                </td>
                                <td class="px-6 py-5 text-right font-bold text-white italic">R$ {{ number_format($serv->receita, 2, ',', '.') }}</td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="w-24 h-1.5 bg-gray-800 rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full bg-violet-600 rounded-full" style="width: {{ $totalReceitaServicos > 0 ? ($serv->receita / $totalReceitaServicos) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-violet-600">
                                            {{ $totalReceitaServicos > 0 ? number_format(($serv->receita / $totalReceitaServicos) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-gray-400">
                                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fa-solid fa-magnifying-glass-chart text-3xl text-gray-200"></i>
                                    </div>
                                    <p class="text-base font-bold text-white uppercase tracking-widest">Sem registros no período</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. Clientes -->
    <div x-show="activeTab === 'clientes'" x-cloak x-transition:enter class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border border-gray-800">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Novos leads (Mês)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold text-white tracking-tighter italic">+{{ $clientesNovos }}</p>
                    <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">crescimento</span>
                </div>
            </div>
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-sm border border-gray-800">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Carteira Ativa</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold text-white tracking-tighter italic">{{ $clientesAtivos }}</p>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">clientes</span>
                </div>
            </div>
            <div class="bg-gray-900 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 shadow-xl text-white relative overflow-hidden group border-none">
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-violet-600 rounded-full -mr-16 -mb-16 opacity-20 blur-3xl group-hover:scale-150 transition-transform"></div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 relative">Retenção Média</p>
                <div class="flex items-baseline gap-2 relative">
                    <p class="text-4xl font-bold text-white tracking-tighter italic">
                        {{ $clientesAtivos > 0 ? number_format($servicosPerformance->sum('qtd') / $clientesAtivos, 1) : 0 }}
                    </p>
                    <span class="text-[10px] font-bold text-violet-400 uppercase tracking-widest">visitas / mês</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800">
            <div class="p-6 border-b border-gray-50 bg-gray-900/80 flex items-center justify-between">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-3">
                    <i class="fa-solid fa-star text-amber-500"></i> Top 10 Clientes VIP (LTV)
                </h3>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-900/50 px-3 py-1 rounded-full border border-gray-800">Vitalício</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800/50 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-5">Perfil do Cliente</th>
                            <th class="px-6 py-5 text-center">Frequência</th>
                            <th class="px-6 py-5 text-right">LTV (Gasto Total)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($clientesRanking as $cli)
                            <tr class="hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-800/50 flex items-center justify-center text-xs font-bold text-gray-400 border border-gray-800 group-hover:bg-violet-900/30 group-hover:text-violet-600 transition-all">
                                            {{ substr($cli->nome, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-white group-hover:text-violet-600 transition-colors uppercase tracking-tight text-xs">{{ $cli->nome }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 bg-violet-900/30 text-violet-600 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-violet-800">
                                        {{ $cli->visitas }} Visitas
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right font-bold text-emerald-600 italic text-base tracking-tighter">R$ {{ number_format($cli->gasto_total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-20 text-center text-gray-400">
                                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fa-solid fa-users text-3xl text-gray-200"></i>
                                    </div>
                                    <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum dado VIP disponível</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Profissionais -->
    <div x-show="activeTab === 'profissionais'" x-cloak x-transition:enter class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($profissionaisPerformance as $prof)
                <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-8 shadow-sm border border-gray-800 flex flex-col sm:flex-row sm:items-center gap-8 relative overflow-hidden group hover:border-violet-200 transition-all active:scale-[0.98]">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-violet-900/30 rounded-full -mr-16 -mt-16 opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="w-20 h-20 bg-gray-900 rounded-[28px] flex items-center justify-center text-3xl font-bold text-white shadow-2xl shrink-0 z-10 border-4 border-white">
                        {{ substr($prof->nome, 0, 1) }}
                    </div>
                    
                    <div class="flex-1 z-10">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight group-hover:text-violet-600 transition-colors">{{ $prof->nome }}</h3>
                        <div class="mt-6 grid grid-cols-2 gap-8">
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Volume de Atendimento</p>
                                <p class="text-2xl font-bold text-white tracking-tighter italic">{{ $prof->atendimentos }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Gerado</p>
                                <p class="text-2xl font-bold text-emerald-500 tracking-tighter italic">R$ {{ number_format($prof->receita_gerada, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="z-10 flex flex-col items-end gap-3 shrink-0">
                         <div class="text-[10px] font-bold text-violet-600 bg-violet-900/30 border border-violet-800 px-4 py-2 rounded-2xl uppercase tracking-tighter shadow-sm">
                            {{ $totalReceitaServicos > 0 ? number_format(($prof->receita_gerada / $totalReceitaServicos) * 100, 0) : 0 }}% do Salão
                         </div>
                         <a href="#" class="text-[10px] font-bold text-gray-400 hover:text-violet-600 uppercase tracking-widest flex items-center gap-2 transition-all">
                            Análise <i class="fa-solid fa-arrow-right-long text-[8px]"></i>
                         </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 py-24 text-center text-gray-400">
                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-user-group text-3xl text-gray-200"></i>
                    </div>
                    <p class="text-base font-bold text-white uppercase tracking-widest">Sem performance registrada</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
