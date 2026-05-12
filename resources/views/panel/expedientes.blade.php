@extends('layouts.app')
@section('title', 'Expedientes')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    professional: null,
    expedientes: [],
    diasSemana: ['Domingo', 'Segunda', 'TerÃ§a', 'Quarta', 'Quinta', 'Sexta', 'SÃ¡bado'],

    openModal(prof) {
        this.professional = prof;
        this.expedientes = [];
        
        // Initialize 7 days
        for (let i = 0; i < 7; i++) {
            let existing = prof.expedientes.find(e => e.dia_semana == i);
            this.expedientes.push({
                dia_semana: i,
                nome: this.diasSemana[i],
                ativo: existing ? existing.ativo : (i > 0 && i < 6), // Mon-Fri active by default
                hora_inicio: existing ? existing.hora_inicio.substring(0, 5) : '08:00',
                hora_fim: existing ? existing.hora_fim.substring(0, 5) : '18:00',
                intervalo_inicio: (existing && existing.intervalo_inicio) ? existing.intervalo_inicio.substring(0, 5) : '12:00',
                intervalo_fim: (existing && existing.intervalo_fim) ? existing.intervalo_fim.substring(0, 5) : '13:00',
            });
        }
        this.showModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Jornada de Trabalho</h1>
            <p class="text-sm text-gray-400 font-medium">Configure os horÃ¡rios de atendimento de cada especialista.</p>
        </div>
    </div>

    <!-- Lista de Profissionais -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800 rounded-[32px]">
        <div class="p-8 border-b border-gray-50 bg-gray-900/30">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Equipe Ativa</h3>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($profissionais as $profissional)
                <div class="p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:bg-gray-900/80 transition-colors group">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-[24px] bg-violet-900/30 flex items-center justify-center text-violet-600 font-bold text-xl shadow-sm border border-violet-800 group-hover:bg-violet-600 group-hover:text-white transition-all">
                            {{ $profissional->initials }}
                        </div>
                        <div>
                            <div class="font-bold text-white text-xl group-hover:text-violet-600 transition-colors">{{ $profissional->nome }}</div>
                            <div class="flex items-center gap-2 mt-1.5">
                                @php $diasAtivos = $profissional->expedientes->where('ativo', true)->count(); @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $diasAtivos > 0 ? 'bg-emerald-900/30 text-emerald-600 border border-emerald-800' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $diasAtivos > 0 ? "$diasAtivos dias ativos" : 'Inativo' }}
                                </span>
                                @if($diasAtivos > 0)
                                    <span class="text-[10px] text-gray-400 font-medium ml-1">DisponÃ­vel para agendamentos</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button @click="openModal({{ json_encode($profissional) }})" class="flex items-center justify-center gap-3 bg-gray-900/50 border border-gray-700 text-gray-300 px-8 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all shadow-sm active:scale-95 italic">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Configurar HorÃ¡rios
                    </button>
                </div>
            @empty
                <div class="p-20 text-center text-gray-400">
                    <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-user-slash text-3xl text-gray-200"></i>
                    </div>
                    <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum especialista</p>
                    <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Cadastre alguÃ©m na equipe para definir expedientes.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Configurar Expediente -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-gray-800">
                <form action="{{ route('panel.expedientes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="profissional_id" :value="professional ? professional.id : ''">

                    <div class="bg-gray-900/50 px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-600 border border-violet-800 shadow-sm">
                                    <i class="fa-solid fa-business-time text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Expediente Semanal</h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5" x-text="professional ? 'Personalizando jornada de ' + professional.nome : ''"></p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="overflow-x-auto no-scrollbar border border-gray-800 rounded-[24px]">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-900/80">
                                    <tr class="text-[9px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-800">
                                        <th class="text-left py-5 px-6">Dia da Semana</th>
                                        <th class="text-center py-5 px-6">Status</th>
                                        <th class="text-center py-5 px-6">InÃ­cio</th>
                                        <th class="text-center py-5 px-6">Fim</th>
                                        <th class="text-center py-5 px-6">InÃ­cio Intervalo</th>
                                        <th class="text-center py-5 px-6">Fim Intervalo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <template x-for="(day, index) in expedientes" :key="index">
                                        <tr class="hover:bg-gray-800/50 transition-colors">
                                            <td class="py-5 px-6 font-bold text-white" x-text="day.nome"></td>
                                            <td class="py-5 px-6 text-center">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" :name="'expedientes['+index+'][ativo]'" value="1" x-model="day.ativo" class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-gray-900/50 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600 shadow-inner"></div>
                                                </label>
                                                <input type="hidden" :name="'expedientes['+index+'][dia_semana]'" :value="day.dia_semana">
                                            </td>
                                            <td class="py-5 px-6 text-center">
                                                <input type="time" :name="'expedientes['+index+'][hora_inicio]'" x-model="day.hora_inicio" :disabled="!day.ativo" class="bg-gray-800/50 border-gray-800 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed">
                                            </td>
                                            <td class="py-5 px-6 text-center">
                                                <input type="time" :name="'expedientes['+index+'][hora_fim]'" x-model="day.hora_fim" :disabled="!day.ativo" class="bg-gray-800/50 border-gray-800 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed">
                                            </td>
                                            <td class="py-5 px-6 text-center">
                                                <input type="time" :name="'expedientes['+index+'][intervalo_inicio]'" x-model="day.intervalo_inicio" :disabled="!day.ativo" class="bg-gray-800/50 border-gray-800 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed">
                                            </td>
                                            <td class="py-5 px-6 text-center">
                                                <input type="time" :name="'expedientes['+index+'][intervalo_fim]'" x-model="day.intervalo_fim" :disabled="!day.ativo" class="bg-gray-800/50 border-gray-800 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-900/80 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto italic">Confirmar AlteraÃ§Ãµes</button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
