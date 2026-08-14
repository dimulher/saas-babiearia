@extends('layouts.funcionario')
@section('title', 'Horários Bloqueados')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Horários Bloqueados</h1>
            <p class="text-sm text-gray-400 font-medium">Reserve períodos de indisponibilidade na sua agenda.</p>
        </div>
        <button @click="showModal = true"
            class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-900/30 transition-all">
            <i class="fa-solid fa-calendar-xmark text-xs"></i> Novo Bloqueio
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-green-400 font-medium flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Lista --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-800/50 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seus Bloqueios</h3>
            <span class="text-[9px] font-bold text-green-500 bg-green-900/30 px-3 py-1 rounded-full border border-green-800/50 uppercase tracking-widest">
                {{ $bloqueios->count() }} registro(s)
            </span>
        </div>

        @if($bloqueios->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <i class="fa-solid fa-calendar-check text-4xl text-gray-700 mb-4"></i>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Agenda disponível</p>
                <p class="text-xs text-gray-600 mt-1">Nenhum horário bloqueado no momento.</p>
            </div>
        @else
            <div class="divide-y divide-gray-800/50">
                @foreach($bloqueios as $bloqueio)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-900/30 transition-colors group">
                        <div class="w-10 h-10 bg-rose-900/30 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-ban text-rose-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white">
                                {{ $bloqueio->data_inicio->format('d/m/Y') }}
                                @if($bloqueio->data_inicio->format('Y-m-d') !== $bloqueio->data_fim->format('Y-m-d'))
                                    → {{ $bloqueio->data_fim->format('d/m/Y') }}
                                @endif
                            </p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5 flex items-center gap-2">
                                <i class="fa-regular fa-clock text-[8px]"></i>
                                {{ $bloqueio->data_inicio->format('H:i') }} – {{ $bloqueio->data_fim->format('H:i') }}
                                @if($bloqueio->motivo)
                                    <span class="text-gray-700">·</span>
                                    <span class="normal-case font-medium text-gray-500">{{ $bloqueio->motivo }}</span>
                                @endif
                            </p>
                        </div>
                        <form action="{{ route('funcionario.bloqueios.destroy', $bloqueio->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Remover este bloqueio?')"
                                class="opacity-0 group-hover:opacity-100 w-8 h-8 flex items-center justify-center text-gray-600 hover:text-rose-400 hover:bg-rose-900/20 rounded-xl transition-all">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[70] overflow-y-auto flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-transition.opacity @click="showModal = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-6"
             class="relative w-full sm:max-w-md bg-[#111827] border border-gray-800 rounded-t-3xl sm:rounded-3xl shadow-2xl z-10">
            <form action="{{ route('funcionario.bloqueios.store') }}" method="POST">
                @csrf
                <div class="px-6 pt-6 pb-5">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-rose-900/30 rounded-xl flex items-center justify-center border border-rose-800/50">
                                <i class="fa-solid fa-calendar-xmark text-rose-400"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Criar Bloqueio</h3>
                                <p class="text-[10px] text-gray-500">Indisponibilize um período na sua agenda.</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false"
                            class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-white hover:bg-gray-800 rounded-xl transition-all">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Data Início</label>
                                <input type="date" name="data_inicio" required value="{{ now()->format('Y-m-d') }}"
                                    class="block w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Data Fim</label>
                                <input type="date" name="data_fim" required value="{{ now()->format('Y-m-d') }}"
                                    class="block w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Hora Início</label>
                                <input type="time" name="hora_inicio" required
                                    class="block w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Hora Fim</label>
                                <input type="time" name="hora_fim" required
                                    class="block w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Motivo (opcional)</label>
                            <input type="text" name="motivo" placeholder="Ex: Folga, Curso, Reunião..."
                                class="block w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm font-medium text-white placeholder-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none transition-all">
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" @click="showModal = false"
                        class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-900/30 transition-all">
                        Confirmar Bloqueio
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
