@extends('layouts.app')
@section('title', 'Agendamentos Recorrentes')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    diasSemana: ['Domingo', 'Segunda', 'TerÃ§a', 'Quarta', 'Quinta', 'Sexta', 'SÃ¡bado']
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Atendimentos Recorrentes</h1>
            <p class="text-sm text-gray-400 font-medium">Gerencie clientes com horÃ¡rios periÃ³dicos fixos no estabelecimento.</p>
        </div>
        <button @click="showModal = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-violet-200">
            <i class="fa-solid fa-calendar-plus text-xs"></i> Novo Recorrente
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 flex flex-wrap items-center gap-4 border border-gray-800 shadow-sm rounded-[32px]">
        <div class="relative flex-1 min-w-[250px]">
            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" placeholder="Pesquisar por cliente ou especialista..." class="block w-full pl-12 pr-4 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
        </div>
        <div class="flex items-center gap-3 bg-gray-800/50 px-5 py-4 rounded-2xl border border-gray-800">
            <i class="fa-solid fa-user-tie text-violet-500 text-xs"></i>
            <select class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8 cursor-pointer">
                <option>Todos profissionais</option>
                @foreach($profissionais as $p)
                    <option value="{{ $p->id }}">{{ $p->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-3 bg-gray-800/50 px-5 py-4 rounded-2xl border border-gray-800">
            <i class="fa-solid fa-filter text-violet-500 text-xs"></i>
            <select class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8 cursor-pointer">
                <option>Todos status</option>
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800 rounded-[32px]">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Cliente / Contato</th>
                        <th class="px-8 py-5">Especialista</th>
                        <th class="px-8 py-5 text-center">Procedimento</th>
                        <th class="px-8 py-5 text-center">FrequÃªncia</th>
                        <th class="px-8 py-5 text-center">HorÃ¡rio</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">AÃ§Ãµes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recorrentes as $rec)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-white group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ $rec->cliente->nome }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 opacity-60">{{ $rec->cliente->telefone }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-violet-900/30 flex items-center justify-center text-violet-600 text-[10px] font-bold border border-violet-800">
                                        {{ $rec->profissional->initials }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-300">{{ $rec->profissional->nome }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 bg-gray-800/50 px-3 py-1.5 rounded-lg border border-gray-800">{{ $rec->servico->nome }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-violet-600 flex flex-col items-center">
                                    <span class="opacity-40 text-[8px]">Toda</span>
                                    {{ $rec->dia_semana_nome }}
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2 text-white font-bold italic">
                                    <i class="fa-regular fa-clock text-[10px] text-violet-400"></i>
                                    {{ substr($rec->hora, 0, 5) }}
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <form action="{{ route('panel.agendamentos-recorrentes.toggle', $rec->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border transition-all {{ $rec->ativo ? 'bg-emerald-900/30 text-emerald-600 border-emerald-800 shadow-sm shadow-emerald-900/20' : 'bg-gray-800/50 text-gray-400 border-gray-800 opacity-60' }}">
                                        {{ $rec->ativo ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('panel.agendamentos-recorrentes.destroy', $rec->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Deseja remover este agendamento recorrente?')" class="text-gray-300 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-calendar-days text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum recorrente cadastrado</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Clique em "Novo Recorrente" para automatizar seus horÃ¡rios.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo Recorrente -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.agendamentos-recorrentes.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-900/50 px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-violet-900/30 rounded-2xl flex items-center justify-center text-violet-600 shadow-sm border border-violet-800">
                                    <i class="fa-solid fa-calendar-check text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Nova RecorrÃªncia</h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">Defina um horÃ¡rio fixo para o cliente.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cliente</label>
                                <select name="cliente_id" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none cursor-pointer appearance-none">
                                    <option value="">Selecione o cliente</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Especialista</label>
                                    <select name="profissional_id" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none cursor-pointer appearance-none">
                                        <option value="">Selecione</option>
                                        @foreach($profissionais as $p)
                                            <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">ServiÃ§o</label>
                                    <select name="servico_id" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none cursor-pointer appearance-none">
                                        <option value="">Selecione</option>
                                        @foreach($servicos as $s)
                                            <option value="{{ $s->id }}">{{ $s->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Dia da Semana</label>
                                    <select name="dia_semana" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none cursor-pointer appearance-none">
                                        <template x-for="(day, index) in diasSemana" :key="index">
                                            <option :value="index" x-text="day"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">HorÃ¡rio Fixo</label>
                                    <input type="time" name="hora" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900/80 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto italic">Confirmar RecorrÃªncia</button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
