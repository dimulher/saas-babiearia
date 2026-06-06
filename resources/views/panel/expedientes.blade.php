@extends('layouts.app')
@section('title', 'Expedientes')

@section('content')
<div class="space-y-6" x-data="{
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    professional: null,
    expedientes: [],
    diasSemana: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    openModal(prof) {
        this.professional = prof;
        this.expedientes = [];
        for (let i = 0; i < 7; i++) {
            let existing = prof.expedientes.find(e => e.dia_semana == i);
            this.expedientes.push({
                dia_semana: i,
                nome: this.diasSemana[i],
                ativo: existing ? existing.ativo : (i > 0 && i < 6),
                hora_inicio: existing ? existing.hora_inicio.substring(0, 5) : '08:00',
                hora_fim: existing ? existing.hora_fim.substring(0, 5) : '18:00',
                intervalo_inicio: (existing && existing.intervalo_inicio) ? existing.intervalo_inicio.substring(0, 5) : '12:00',
                intervalo_fim: (existing && existing.intervalo_fim) ? existing.intervalo_fim.substring(0, 5) : '13:00',
            });
        }
        this.showModal = true;
    }
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Jornada de Trabalho</h1>
            <p class="text-sm text-gray-400 font-medium">Configure os horários de atendimento de cada especialista.</p>
        </div>
    </div>

    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-800/50">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Equipe Ativa</h3>
        </div>
        <div class="divide-y divide-gray-800/50">
            @forelse($profissionais as $profissional)
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 hover:bg-gray-900/30 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-green-900/30 flex items-center justify-center text-green-400 font-bold text-lg border border-green-800/50 group-hover:bg-green-500 group-hover:text-white transition-all">
                            {{ $profissional->initials }}
                        </div>
                        <div>
                            <div class="font-bold text-white text-base group-hover:text-green-400 transition-colors">{{ $profissional->nome }}</div>
                            <div class="flex items-center gap-2 mt-1.5">
                                @php $diasAtivos = $profissional->expedientes->where('ativo', true)->count(); @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $diasAtivos > 0 ? 'bg-emerald-900/30 text-emerald-400 border border-emerald-800/50' : 'bg-rose-900/30 text-rose-400 border border-rose-800/50' }}">
                                    {{ $diasAtivos > 0 ? "$diasAtivos dias ativos" : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <button @click="openModal({{ json_encode($profissional) }})"
                        class="flex items-center gap-2 bg-gray-900 border border-gray-700 text-gray-300 px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 hover:text-white transition-all">
                        <i class="fa-solid fa-clock-rotate-left"></i> Configurar Horários
                    </button>
                </div>
            @empty
                <div class="p-16 text-center">
                    <i class="fa-solid fa-user-slash text-3xl text-gray-700 mb-3 block"></i>
                    <p class="text-sm font-bold text-white">Nenhum especialista</p>
                    <p class="text-xs text-gray-500 mt-1">Cadastre alguém na equipe para definir expedientes.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Expediente -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-4xl border border-gray-800 z-10 max-h-[90vh] overflow-y-auto">
            <form action="{{ route('panel.expedientes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="profissional_id" :value="professional ? professional.id : ''">
                <div class="px-7 pt-7 pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50">
                                <i class="fa-solid fa-business-time text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Expediente Semanal</h3>
                                <p class="text-xs text-gray-500 mt-0.5" x-text="professional ? 'Configurando jornada de ' + professional.nome : ''"></p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="w-9 h-9 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="overflow-x-auto no-scrollbar border border-gray-800 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-900/70">
                                <tr class="text-[9px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-800">
                                    <th class="text-left py-4 px-5">Dia</th>
                                    <th class="text-center py-4 px-5">Ativo</th>
                                    <th class="text-center py-4 px-5">Início</th>
                                    <th class="text-center py-4 px-5">Fim</th>
                                    <th class="text-center py-4 px-5">Intervalo Início</th>
                                    <th class="text-center py-4 px-5">Intervalo Fim</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/50">
                                <template x-for="(day, index) in expedientes" :key="index">
                                    <tr class="hover:bg-gray-800/30 transition-colors">
                                        <td class="py-4 px-5 font-semibold text-white text-sm" x-text="day.nome"></td>
                                        <td class="py-4 px-5 text-center">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" :name="'expedientes['+index+'][ativo]'" value="1" x-model="day.ativo" class="sr-only peer">
                                                <div class="w-10 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                            </label>
                                            <input type="hidden" :name="'expedientes['+index+'][dia_semana]'" :value="day.dia_semana">
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            <input type="time" :name="'expedientes['+index+'][hora_inicio]'" x-model="day.hora_inicio" :disabled="!day.ativo"
                                                class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed text-white">
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            <input type="time" :name="'expedientes['+index+'][hora_fim]'" x-model="day.hora_fim" :disabled="!day.ativo"
                                                class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed text-white">
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            <input type="time" :name="'expedientes['+index+'][intervalo_inicio]'" x-model="day.intervalo_inicio" :disabled="!day.ativo"
                                                class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed text-white">
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            <input type="time" :name="'expedientes['+index+'][intervalo_fim]'" x-model="day.intervalo_fim" :disabled="!day.ativo"
                                                class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed text-white">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-gray-900/60 border-t border-gray-800 px-7 py-5 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Confirmar Alterações</button>
                    <button type="button" @click="showModal = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
