@extends('layouts.app')
@section('title', 'Bloquear HorÃ¡rios')

@section('content')
<div class="space-y-6" x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Bloqueio de Agenda</h1>
            <p class="text-sm text-gray-400 font-medium">Reserve horÃ¡rios para pausas, reuniÃµes ou folgas da equipe.</p>
        </div>
        <button @click="showModal = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-violet-200">
            <i class="fa-solid fa-calendar-xmark text-xs"></i> Novo Bloqueio
        </button>
    </div>

    <!-- Lista de Bloqueios -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800 rounded-[32px]">
        <div class="p-8 border-b border-gray-50 bg-gray-900/30 flex justify-between items-center">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bloqueios Ativos e Futuros</h3>
            <span class="text-[9px] font-bold text-violet-600 bg-violet-900/30 px-3 py-1 rounded-full border border-violet-800 uppercase tracking-widest">Sincronizado</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Especialista</th>
                        <th class="px-8 py-5">PerÃ­odo</th>
                        <th class="px-8 py-5">Motivo / DescriÃ§Ã£o</th>
                        <th class="px-8 py-5 text-right">AÃ§Ãµes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bloqueios as $bloqueio)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ $bloqueio->profissional->initials ?? '?' }}
                                    </div>
                                    <span class="font-bold text-white group-hover:text-violet-600 transition-colors">{{ $bloqueio->profissional->nome ?? 'Todos' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-white font-bold text-xs uppercase tracking-tight">
                                        <i class="fa-regular fa-calendar text-violet-500"></i>
                                        {{ $bloqueio->data->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ substr($bloqueio->hora_inicio, 0, 5) }} Ã s {{ substr($bloqueio->hora_fim, 0, 5) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm text-gray-400 font-medium italic">"{{ $bloqueio->motivo ?? 'Sem descriÃ§Ã£o' }}"</p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('panel.bloquear-horarios.destroy', $bloqueio->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Deseja remover este bloqueio?')" class="text-gray-300 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-calendar-check text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum horÃ¡rio bloqueado</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">A agenda estÃ¡ 100% disponÃ­vel para novos clientes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo Bloqueio -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.bloquear-horarios.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-900/50 px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-violet-900/30 rounded-2xl flex items-center justify-center text-violet-600 border border-violet-800 shadow-sm">
                                    <i class="fa-solid fa-calendar-xmark text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Criar Bloqueio</h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">Indisponibilize horÃ¡rios especÃ­ficos.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Profissional Afetado</label>
                                <select name="profissional_id" class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">Toda a Equipe</option>
                                    @foreach($profissionais as $p)
                                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Data do Bloqueio</label>
                                <input type="date" name="data" required value="{{ now()->format('Y-m-d') }}" class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Hora InÃ­cio</label>
                                    <input type="time" name="hora_inicio" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Hora Fim</label>
                                    <input type="time" name="hora_fim" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Motivo / Notas</label>
                                <textarea name="motivo" rows="2" placeholder="Ex: HorÃ¡rio de almoÃ§o, ReuniÃ£o tÃ©cnica..." class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900/80 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto italic">Confirmar Bloqueio</button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
