@extends('layouts.app')
@section('title', 'Agendamentos Recorrentes')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight uppercase">Agendamentos Recorrentes</h1>
            <p class="text-sm text-gray-400 font-medium">Clientes fixos com horário reservado toda semana.</p>
        </div>
        <button @click="showModal = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-900/30">
            <i class="fa-solid fa-rotate text-xs"></i> Novo Recorrente
        </button>
    </div>

    {{-- Feedback --}}
    @if(session('success'))
        <div class="bg-green-900/30 border border-green-800/50 text-green-400 px-5 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabela --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-gray-800/50 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agenda Semanal Fixa</h3>
            <span class="text-[9px] font-bold text-green-500 bg-green-900/30 px-3 py-1 rounded-full border border-green-800 uppercase tracking-widest">
                {{ $recorrentes->count() }} {{ $recorrentes->count() === 1 ? 'registro' : 'registros' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-3 sm:px-6 py-4">Cliente</th>
                        <th class="px-3 sm:px-6 py-4">Profissional</th>
                        <th class="px-3 sm:px-6 py-4">Serviço</th>
                        <th class="px-3 sm:px-6 py-4 text-center">Dia / Hora</th>
                        <th class="px-3 sm:px-6 py-4 text-center">Status</th>
                        <th class="px-3 sm:px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($recorrentes as $rec)
                        <tr class="hover:bg-gray-900/40 transition-colors group">
                            <td class="px-3 sm:px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 font-bold text-xs border border-green-800/50 shrink-0">
                                        {{ strtoupper(substr($rec->cliente->nome ?? '?', 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-white text-sm group-hover:text-green-400 transition-colors">
                                        {{ $rec->cliente->nome ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-5">
                                <span class="text-gray-300 text-sm">{{ $rec->profissional->nome ?? '—' }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-5">
                                <span class="text-gray-300 text-sm">{{ $rec->servico->nome ?? '—' }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-5 text-center">
                                <div class="space-y-1">
                                    <div class="text-white font-bold text-xs uppercase tracking-wide">
                                        {{ $rec->dia_semana_nome }}
                                    </div>
                                    <div class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fa-regular fa-clock mr-1 text-green-500"></i>{{ substr($rec->hora, 0, 5) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-5 text-center">
                                @if($rec->ativo)
                                    <span class="inline-flex items-center gap-1.5 text-[9px] font-bold text-emerald-400 bg-emerald-900/30 border border-emerald-800/50 px-3 py-1.5 rounded-full uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[9px] font-bold text-gray-500 bg-gray-800/50 border border-gray-700/50 px-3 py-1.5 rounded-full uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span> Pausado
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('panel.agendamentos-recorrentes.toggle', $rec->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $rec->ativo ? 'Pausar' : 'Ativar' }}"
                                            class="{{ $rec->ativo ? 'text-emerald-400 hover:text-yellow-400' : 'text-gray-500 hover:text-emerald-400' }} transition-colors">
                                            <i class="fa-solid {{ $rec->ativo ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('panel.agendamentos-recorrentes.destroy', $rec->id) }}" method="POST"
                                        onsubmit="return confirm('Remover agendamento recorrente de {{ addslashes($rec->cliente->nome ?? '') }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Excluir" class="text-gray-500 hover:text-rose-400 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-rotate text-3xl text-gray-600"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum agendamento recorrente</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Cadastre clientes com horário fixo semanal.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Novo Recorrente --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">

                <form action="{{ route('panel.agendamentos-recorrentes.store') }}" method="POST">
                    @csrf
                    <div class="px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-green-900/30 rounded-2xl flex items-center justify-center text-green-500 border border-green-800">
                                    <i class="fa-solid fa-rotate text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Novo Recorrente</h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">Horário fixo semanal para um cliente.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cliente</label>
                                <select name="cliente_id" required class="block w-full px-5 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:border-green-600 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">Selecione o cliente</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Profissional</label>
                                <select name="profissional_id" required class="block w-full px-5 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:border-green-600 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">Selecione o profissional</option>
                                    @foreach($profissionais as $p)
                                        <option value="{{ $p->id }}" {{ old('profissional_id') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                                @error('profissional_id') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Serviço</label>
                                <select name="servico_id" required class="block w-full px-5 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:border-green-600 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">Selecione o serviço</option>
                                    @foreach($servicos as $s)
                                        <option value="{{ $s->id }}" {{ old('servico_id') == $s->id ? 'selected' : '' }}>{{ $s->nome }}</option>
                                    @endforeach
                                </select>
                                @error('servico_id') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Dia da Semana</label>
                                    <select name="dia_semana" required class="block w-full px-5 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:border-green-600 transition-all outline-none appearance-none cursor-pointer">
                                        <option value="">Dia</option>
                                        <option value="0" {{ old('dia_semana') == '0' ? 'selected' : '' }}>Domingo</option>
                                        <option value="1" {{ old('dia_semana') == '1' ? 'selected' : '' }}>Segunda</option>
                                        <option value="2" {{ old('dia_semana') == '2' ? 'selected' : '' }}>Terça</option>
                                        <option value="3" {{ old('dia_semana') == '3' ? 'selected' : '' }}>Quarta</option>
                                        <option value="4" {{ old('dia_semana') == '4' ? 'selected' : '' }}>Quinta</option>
                                        <option value="5" {{ old('dia_semana') == '5' ? 'selected' : '' }}>Sexta</option>
                                        <option value="6" {{ old('dia_semana') == '6' ? 'selected' : '' }}>Sábado</option>
                                    </select>
                                    @error('dia_semana') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Horário</label>
                                    <input type="time" name="hora" required value="{{ old('hora') }}"
                                        class="block w-full px-5 py-4 bg-gray-800/50 border border-gray-700 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:border-green-600 transition-all outline-none">
                                    @error('hora') <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900/80 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-2xl px-10 py-4 bg-green-500 hover:bg-green-600 text-[10px] font-bold text-white uppercase tracking-widest transition-all shadow-xl shadow-green-900/20 italic">
                            Cadastrar Recorrente
                        </button>
                        <button type="button" @click="showModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-transparent text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
