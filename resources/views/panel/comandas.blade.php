@extends('layouts.app')
@section('title', 'Comandas')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, tab: 'abertas' }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Gestão de Comandas</h1>
            <p class="text-sm text-gray-400 font-medium">Controle o atendimento do seu estabelecimento em tempo real.</p>
        </div>
        <button @click="showModal = true" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-lg shadow-green-900/30 uppercase tracking-wider">
            <i class="fa-solid fa-plus text-xs"></i> Nova Comanda
        </button>
    </div>

    <!-- Tabs -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-1 p-1 bg-gray-900 rounded-xl border border-gray-800 w-max min-w-full">
            <button @click="tab = 'abertas'"
                :class="tab === 'abertas' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                Em Aberto
            </button>
            <button @click="tab = 'fechadas'"
                :class="tab === 'fechadas' ? 'bg-green-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                Finalizadas
            </button>
        </div>
    </div>

    <!-- Aba Abertas -->
    <div x-show="tab === 'abertas'" x-cloak>
        @if($comandasAbertas->isEmpty())
            <div class="bg-[#111827] border border-dashed border-gray-700 rounded-2xl p-16 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-900/50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-receipt text-2xl text-gray-600"></i>
                </div>
                <p class="text-sm font-bold text-white">Sem comandas abertas</p>
                <p class="text-xs text-gray-500 mt-1">Inicie um atendimento clicando em "Nova Comanda"</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($comandasAbertas as $comanda)
                    <div class="bg-[#111827] border border-gray-800 hover:border-green-700/50 rounded-2xl p-6 flex flex-col transition-all group">
                        <div class="flex justify-between items-start mb-5">
                            <div class="flex-1">
                                <h3 class="font-bold text-white text-base group-hover:text-green-400 transition-colors">{{ $comanda->cliente_nome }}</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2 mt-1.5">
                                    <i class="fa-solid fa-user-tie text-green-500 text-[9px]"></i>
                                    {{ $comanda->profissional->nome ?? 'Sem Profissional' }}
                                </p>
                            </div>
                            <span class="bg-emerald-900/30 text-emerald-400 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800/50">Aberta</span>
                        </div>
                        <div class="mt-auto pt-5 border-t border-gray-800 flex justify-between items-center">
                            <p class="font-bold text-white text-xl tracking-tight">R$ {{ number_format($comanda->total, 2, ',', '.') }}</p>
                            <a href="{{ route('panel.comandas.show', $comanda->id) }}" class="text-green-400 hover:text-green-300 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                Detalhes <i class="fa-solid fa-chevron-right text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Aba Fechadas -->
    <div x-show="tab === 'fechadas'" x-cloak>
        @if($comandasFechadas->isEmpty())
            <div class="bg-[#111827] border border-dashed border-gray-700 rounded-2xl p-16 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-900/50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-box-archive text-2xl text-gray-600"></i>
                </div>
                <p class="text-sm font-bold text-white">Sem histórico</p>
                <p class="text-xs text-gray-500 mt-1">As comandas finalizadas aparecerão aqui</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($comandasFechadas as $comanda)
                    <div class="bg-[#111827] border border-gray-800 rounded-2xl p-6 flex flex-col opacity-70 hover:opacity-100 transition-all">
                        <div class="flex justify-between items-start mb-5">
                            <div class="flex-1">
                                <h3 class="font-bold text-white text-base">{{ $comanda->cliente_nome }}</h3>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-2 mt-1.5">
                                    <i class="fa-solid fa-user-tie text-[9px]"></i>
                                    {{ $comanda->profissional->nome ?? 'Sem Profissional' }}
                                </p>
                            </div>
                            <span class="bg-gray-800 text-gray-500 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-gray-700">Finalizada</span>
                        </div>
                        <div class="mt-auto pt-5 border-t border-gray-800 flex justify-between items-center">
                            <p class="font-bold text-white text-xl tracking-tight">R$ {{ number_format($comanda->total, 2, ',', '.') }}</p>
                            <a href="{{ route('panel.comandas.show', $comanda->id) }}" class="text-gray-400 hover:text-gray-300 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1">
                                Ver Detalhes <i class="fa-solid fa-chevron-right text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal Nova Comanda -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-lg border border-gray-800 z-10">
            <form action="{{ route('panel.comandas.store') }}" method="POST">
                @csrf
                <div class="px-7 pt-7 pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50">
                                <i class="fa-solid fa-receipt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Iniciar Atendimento</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Preencha os dados para abrir a comanda.</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="w-9 h-9 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nome do Cliente</label>
                            <input type="text" name="cliente_nome" required placeholder="Ex: Maria Oliveira"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Especialista</label>
                            <select name="profissional_id" required
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                                <option value="">Selecione o profissional</option>
                                @foreach($profissionais as $profissional)
                                    <option value="{{ $profissional->id }}">{{ $profissional->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Observações</label>
                            <textarea name="observacoes" rows="2" placeholder="Informações adicionais..."
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900/60 border-t border-gray-800 px-7 py-5 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-green-900/20">Abrir Comanda</button>
                    <button type="button" @click="showModal = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
