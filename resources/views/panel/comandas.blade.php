@extends('layouts.app')
@section('title', 'Comandas')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, tab: 'abertas' }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">GestÃ£o de Comandas</h1>
            <p class="text-sm text-gray-400 font-medium">Controle o atendimento do seu estabelecimento em tempo real.</p>
        </div>
        <button @click="showModal = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3 rounded-2xl text-sm font-bold uppercase tracking-widest shadow-xl shadow-violet-200">
            <i class="fa-solid fa-plus text-xs"></i> Nova Comanda
        </button>
    </div>

    <!-- Tabs status -->
    <div class="flex gap-2 p-1 bg-gray-800 rounded-2xl w-fit">
        <button @click="tab = 'abertas'" 
            :class="tab === 'abertas' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'"
            class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
            Em Aberto
        </button>
        <button @click="tab = 'fechadas'" 
            :class="tab === 'fechadas' ? 'bg-gray-900/50 text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-300'"
            class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
            Finalizadas
        </button>
    </div>

    <!-- Container das Abas -->
    <div class="w-full">
        
        <!-- Aba Abertas -->
        <div x-show="tab === 'abertas'" x-cloak x-transition:enter>
            @if($comandasAbertas->isEmpty())
                <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-16 flex flex-col items-center justify-center text-gray-400 border border-dashed border-gray-700">
                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-receipt text-3xl text-gray-200"></i>
                    </div>
                    <p class="text-base font-bold text-white uppercase tracking-widest">Sem comandas abertas</p>
                    <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Inicie um atendimento clicando em "Nova Comanda"</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($comandasAbertas as $comanda)
                        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 flex flex-col hover:border-violet-300 transition-all group shadow-sm">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex-1">
                                    <h3 class="font-bold text-white text-lg group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ $comanda->cliente_nome }}</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2 mt-2">
                                        <i class="fa-solid fa-user-tie text-violet-500"></i>
                                        {{ $comanda->profissional->nome ?? 'Sem Profissional' }}
                                    </p>
                                </div>
                                <span class="bg-emerald-900/30 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800">Aberta</span>
                            </div>
                            <div class="mt-auto pt-6 border-t border-gray-50 flex justify-between items-center">
                                <p class="font-bold text-white text-2xl tracking-tighter">R$ {{ number_format($comanda->total, 2, ',', '.') }}</p>
                                <a href="{{ route('panel.comandas.show', $comanda->id) }}" class="text-violet-600 hover:text-violet-800 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                    Detalhes <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Aba Fechadas -->
        <div x-show="tab === 'fechadas'" x-cloak x-transition:enter>
            @if($comandasFechadas->isEmpty())
                <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-16 flex flex-col items-center justify-center text-gray-400 border border-dashed border-gray-700">
                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-box-archive text-3xl text-gray-200"></i>
                    </div>
                    <p class="text-base font-bold text-white uppercase tracking-widest">Sem histÃ³rico</p>
                    <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">As comandas finalizadas aparecerÃ£o aqui</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($comandasFechadas as $comanda)
                        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 flex flex-col opacity-80 hover:opacity-100 transition-all shadow-sm">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex-1">
                                    <h3 class="font-bold text-white text-lg uppercase tracking-tight">{{ $comanda->cliente_nome }}</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2 mt-2">
                                        <i class="fa-solid fa-user-tie"></i>
                                        {{ $comanda->profissional->nome ?? 'Sem Profissional' }}
                                    </p>
                                </div>
                                <span class="bg-gray-800 text-gray-500 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest">Finalizada</span>
                            </div>
                            <div class="mt-auto pt-6 border-t border-gray-50 flex justify-between items-center">
                                <p class="font-bold text-white text-2xl tracking-tighter">R$ {{ number_format($comanda->total, 2, ',', '.') }}</p>
                                <a href="{{ route('panel.comandas.show', $comanda->id) }}" class="text-gray-400 hover:text-gray-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1">
                                    Ver Detalhes <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Nova Comanda -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.comandas.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-900/50 px-6 pt-8 pb-6 sm:p-8">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-2xl bg-violet-900/30 sm:mx-0">
                                <i class="fa-solid fa-receipt text-violet-600 text-xl"></i>
                            </div>
                            <div class="mt-4 text-center sm:mt-0 sm:ml-6 sm:text-left w-full">
                                <h3 class="text-xl font-bold text-white uppercase tracking-tight" id="modal-title">
                                    Iniciar Atendimento
                                </h3>
                                <p class="text-xs text-gray-400 font-medium mt-1">Preencha os dados bÃ¡sicos para abrir a comanda.</p>
                                
                                <div class="mt-8 space-y-6">
                                    <div class="space-y-2">
                                        <label for="cliente_nome" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome do Cliente</label>
                                        <input type="text" name="cliente_nome" id="cliente_nome" required placeholder="Ex: Maria Oliveira" 
                                            class="block w-full px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                    </div>

                                    <div class="space-y-2">
                                        <label for="profissional_id" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Especialista / Atendente</label>
                                        <select name="profissional_id" id="profissional_id" required 
                                            class="block w-full px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                            <option value="">Selecione o profissional</option>
                                            @foreach($profissionais as $profissional)
                                                <option value="{{ $profissional->id }}">{{ $profissional->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="observacoes" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">ObservaÃ§Ãµes Internas</label>
                                        <textarea name="observacoes" id="observacoes" rows="2" placeholder="InformaÃ§Ãµes adicionais sobre o atendimento..." 
                                            class="block w-full px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800/50 px-6 py-6 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-lg shadow-violet-900/20 px-6 py-3 bg-violet-600 text-xs font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto">
                            Abrir Comanda
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-6 py-3 bg-gray-900/50 text-xs font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">
                            Voltar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
