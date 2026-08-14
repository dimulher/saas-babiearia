@extends('layouts.funcionario')
@section('title', 'Meus Horários')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto" x-data="{
    expedientes: [],
    diasSemana: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
    init() {
        const existing = @json($profissional->expedientes->keyBy('dia_semana'));
        for (let i = 0; i < 7; i++) {
            const e = existing[i];
            this.expedientes.push({
                dia_semana:       i,
                ativo:            e ? e.ativo : (i > 0 && i < 6),
                hora_inicio:      e ? e.hora_inicio.substring(0, 5) : '08:00',
                hora_fim:         e ? e.hora_fim.substring(0, 5)    : '18:00',
                intervalo_inicio: (e && e.intervalo_inicio) ? e.intervalo_inicio.substring(0, 5) : '12:00',
                intervalo_fim:    (e && e.intervalo_fim)    ? e.intervalo_fim.substring(0, 5)    : '13:00',
            });
        }
    }
}">

    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Meus Horários</h1>
        <p class="text-sm text-gray-400 font-medium">Configure seus dias e horários de atendimento.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-green-400 font-medium flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('funcionario.horarios.store') }}" method="POST">
        @csrf

        <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800/50 flex items-center gap-3">
                <div class="w-9 h-9 bg-green-900/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-business-time text-green-400 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Jornada Semanal</h3>
                    <p class="text-[10px] text-gray-500">{{ $profissional->nome }}</p>
                </div>
            </div>

            {{-- Desktop: tabela --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-900/60">
                        <tr class="text-[9px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-800">
                            <th class="text-left py-4 px-5 w-36">Dia</th>
                            <th class="text-center py-4 px-4 w-20">Ativo</th>
                            <th class="text-center py-4 px-4">Início</th>
                            <th class="text-center py-4 px-4">Fim</th>
                            <th class="text-center py-4 px-4">Intervalo Início</th>
                            <th class="text-center py-4 px-4">Intervalo Fim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        <template x-for="(day, index) in expedientes" :key="index">
                            <tr class="transition-colors" :class="day.ativo ? 'hover:bg-gray-900/30' : 'opacity-50'">
                                <td class="py-4 px-5">
                                    <p class="font-bold text-white text-sm" x-text="diasSemana[day.dia_semana]"></p>
                                    <input type="hidden" :name="'expedientes['+index+'][dia_semana]'" :value="day.dia_semana">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" :name="'expedientes['+index+'][ativo]'" value="1" x-model="day.ativo" class="sr-only peer">
                                        <div class="w-10 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="time" :name="'expedientes['+index+'][hora_inicio]'" x-model="day.hora_inicio" :disabled="!day.ativo"
                                        class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="time" :name="'expedientes['+index+'][hora_fim]'" x-model="day.hora_fim" :disabled="!day.ativo"
                                        class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="time" :name="'expedientes['+index+'][intervalo_inicio]'" x-model="day.intervalo_inicio" :disabled="!day.ativo"
                                        class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="time" :name="'expedientes['+index+'][intervalo_fim]'" x-model="day.intervalo_fim" :disabled="!day.ativo"
                                        class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 outline-none disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Mobile: cards --}}
            <div class="sm:hidden divide-y divide-gray-800/50">
                <template x-for="(day, index) in expedientes" :key="'m'+index">
                    <div class="p-4 space-y-3" :class="!day.ativo && 'opacity-50'">
                        <input type="hidden" :name="'expedientes['+index+'][dia_semana]'" :value="day.dia_semana">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-white text-sm" x-text="diasSemana[day.dia_semana]"></p>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" :name="'expedientes['+index+'][ativo]'" value="1" x-model="day.ativo" class="sr-only peer">
                                <div class="w-10 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                        <div x-show="day.ativo" class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Início</p>
                                <input type="time" :name="'expedientes['+index+'][hora_inicio]'" x-model="day.hora_inicio"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 outline-none">
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Fim</p>
                                <input type="time" :name="'expedientes['+index+'][hora_fim]'" x-model="day.hora_fim"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 outline-none">
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Intervalo Início</p>
                                <input type="time" :name="'expedientes['+index+'][intervalo_inicio]'" x-model="day.intervalo_inicio"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 outline-none">
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Intervalo Fim</p>
                                <input type="time" :name="'expedientes['+index+'][intervalo_fim]'" x-model="day.intervalo_fim"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-green-500/50 outline-none">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="px-5 py-4 border-t border-gray-800/50 bg-gray-900/30 flex justify-end">
                <button type="submit"
                    class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-green-900/30 transition-all">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Salvar Horários
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
