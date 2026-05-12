@extends('layouts.app')
@section('title', 'Rastreabilidade - GlowSystem')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Rastreabilidade</h1>
            <p class="text-sm text-gray-400 font-medium">Auditoria completa de todas as ações realizadas no sistema.</p>
        </div>
        <a href="{{ route('panel.logs.export', request()->all()) }}" class="btn-premium flex items-center justify-center gap-2 bg-gray-900/50 border border-gray-800 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800/50 transition-all shadow-sm">
            <i class="fa-solid fa-file-export text-xs text-violet-600"></i> Exportar Dados
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-8 rounded-[32px] border border-gray-800 shadow-sm">
        <form action="{{ route('panel.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pesquisar</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-300 group-focus-within:text-violet-600 transition-colors">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ação ou usuário..." 
                        class="block w-full pl-10 pr-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tipo de Evento</label>
                <select name="acao" class="block w-full py-3.5 px-4 bg-gray-800/50 border-gray-800 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none appearance-none cursor-pointer">
                    <option value="todas">Todos os eventos</option>
                    <option value="cliente_criado" {{ request('acao') == 'cliente_criado' ? 'selected' : '' }}>Cliente Cadastrado</option>
                    <option value="servico_criado" {{ request('acao') == 'servico_criado' ? 'selected' : '' }}>Serviço Cadastrado</option>
                    <option value="produto_criado" {{ request('acao') == 'produto_criado' ? 'selected' : '' }}>Produto Cadastrado</option>
                    <option value="profissional_criado" {{ request('acao') == 'profissional_criado' ? 'selected' : '' }}>Profissional Cadastrado</option>
                    <option value="comanda_fechada" {{ request('acao') == 'comanda_fechada' ? 'selected' : '' }}>Comanda Fechada</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Data Específica</label>
                <input type="date" name="data" value="{{ request('data') }}" 
                    class="block w-full py-3.5 px-4 bg-gray-800/50 border-gray-800 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-900 text-white py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-violet-600 transition-all shadow-lg shadow-gray-100 active:scale-95 italic">Aplicar Filtros</button>
            </div>
        </form>
    </div>

    <!-- Tabela de Logs -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm rounded-[32px] border border-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest border-b border-gray-800">
                    <tr>
                        <th class="px-8 py-5 italic">Stamp (Data/Hora)</th>
                        <th class="px-8 py-5">Agente</th>
                        <th class="px-8 py-5 text-center">Ação Realizada</th>
                        <th class="px-8 py-5">Payload / Descrição</th>
                        <th class="px-8 py-5 text-right">IP Origem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-8 py-5 text-gray-400 font-bold italic text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-800/50 flex items-center justify-center text-xs font-bold text-gray-400 border border-gray-800 group-hover:bg-violet-900/30 group-hover:text-violet-600 transition-all shadow-sm">
                                        {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                    </div>
                                    <span class="text-xs font-bold text-white group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ $log->user ? $log->user->name : 'System Core' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border
                                    {{ str_contains($log->acao, 'criado') ? 'bg-emerald-900/30 text-emerald-600 border-emerald-800' : 
                                       (str_contains($log->acao, 'excluido') ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-violet-900/30 text-violet-600 border-violet-800') }}">
                                    {{ str_replace('_', ' ', $log->acao) }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-[11px] text-gray-500 font-medium leading-relaxed max-w-sm">
                                <p class="truncate group-hover:whitespace-normal transition-all">{{ $log->descricao }}</p>
                            </td>
                            <td class="px-8 py-5 text-right text-gray-400 font-mono text-[10px] italic">
                                {{ $log->ip }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-timeline text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhuma atividade detectada</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-8 border-t border-gray-50 bg-gray-900/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
