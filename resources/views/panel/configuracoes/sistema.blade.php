@extends('layouts.app')
@section('title', 'Configurações do Sistema')

@section('content')
<div class="space-y-8 max-w-3xl mx-auto" x-data="{
    notifNovoAgendamento: true,
    notifCancelamento: true,
    notifRelatorioSemanal: false,
    notifLembreteAgenda: true,
    modoManutencao: false,
    confirmacaoAutomatica: false,
    agendamentoOnline: true,
    saved: false,
    save() {
        this.saved = true;
        setTimeout(() => this.saved = false, 2500);
    }
}">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-extrabold text-white uppercase tracking-tight">Configurações do Sistema</h1>
        <p class="text-sm text-gray-400 font-medium mt-1">Gerencie o comportamento da plataforma e do agendamento online.</p>
    </div>

    {{-- Seção: Agendamentos Online --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl p-8 space-y-6 shadow-sm">
        <div class="flex items-center gap-3 pb-5 border-b border-gray-800/50">
            <div class="w-9 h-9 rounded-xl bg-violet-900/40 flex items-center justify-center border border-violet-800/40">
                <i class="fa-solid fa-calendar-check text-violet-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Agendamentos Online</h2>
                <p class="text-[10px] text-gray-500 font-medium">Controle como os clientes podem agendar pelo link público.</p>
            </div>
        </div>

        {{-- Toggle: Agendamento Online Ativo --}}
        <label class="flex items-center justify-between p-4 bg-[#0B0F19] rounded-2xl border border-gray-800 cursor-pointer hover:border-violet-800/50 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-900/30 border border-emerald-800/40 flex items-center justify-center text-emerald-400">
                    <i class="fa-solid fa-globe text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-widest">Agendamento Online Ativo</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">Permite que clientes agendem pelo link público da sua barbearia.</p>
                </div>
            </div>
            <button type="button" @click="agendamentoOnline = !agendamentoOnline"
                :class="agendamentoOnline ? 'bg-violet-600' : 'bg-gray-700'"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-all shadow-inner">
                <span :class="agendamentoOnline ? 'translate-x-6' : 'translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"></span>
            </button>
        </label>

        {{-- Toggle: Confirmação Automática --}}
        <label class="flex items-center justify-between p-4 bg-[#0B0F19] rounded-2xl border border-gray-800 cursor-pointer hover:border-violet-800/50 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-900/30 border border-blue-800/40 flex items-center justify-center text-blue-400">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-widest">Confirmação Automática</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">Confirma agendamentos online automaticamente, sem revisão manual.</p>
                </div>
            </div>
            <button type="button" @click="confirmacaoAutomatica = !confirmacaoAutomatica"
                :class="confirmacaoAutomatica ? 'bg-violet-600' : 'bg-gray-700'"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-all shadow-inner">
                <span :class="confirmacaoAutomatica ? 'translate-x-6' : 'translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"></span>
            </button>
        </label>

        {{-- Intervalo e Antecedência --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Intervalo Entre Atendimentos</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option>Sem intervalo</option>
                    <option selected>10 minutos</option>
                    <option>15 minutos</option>
                    <option>20 minutos</option>
                    <option>30 minutos</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Antecedência Mínima para Agendamento</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option>Sem restrição</option>
                    <option>1 hora antes</option>
                    <option selected>2 horas antes</option>
                    <option>4 horas antes</option>
                    <option>24 horas antes</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Prazo Máximo de Agendamento</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option>7 dias</option>
                    <option selected>30 dias</option>
                    <option>60 dias</option>
                    <option>90 dias</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cancelamento Pelo Cliente até</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option>Não permitido</option>
                    <option>1 hora antes</option>
                    <option selected>2 horas antes</option>
                    <option>6 horas antes</option>
                    <option>24 horas antes</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Seção: Notificações --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl p-8 space-y-4 shadow-sm">
        <div class="flex items-center gap-3 pb-5 border-b border-gray-800/50">
            <div class="w-9 h-9 rounded-xl bg-violet-900/40 flex items-center justify-center border border-violet-800/40">
                <i class="fa-solid fa-bell text-violet-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Notificações</h2>
                <p class="text-[10px] text-gray-500 font-medium">Escolha quando e como você deseja ser avisado.</p>
            </div>
        </div>

        @php
        $toggles = [
            ['key' => 'notifNovoAgendamento', 'icon' => 'fa-calendar-plus', 'color' => 'violet', 'title' => 'Novo Agendamento', 'desc' => 'Receba um aviso cada vez que um cliente agendar online.'],
            ['key' => 'notifCancelamento', 'icon' => 'fa-calendar-xmark', 'color' => 'rose', 'title' => 'Cancelamento de Agendamento', 'desc' => 'Seja notificado quando um agendamento for cancelado.'],
            ['key' => 'notifLembreteAgenda', 'icon' => 'fa-clock', 'color' => 'amber', 'title' => 'Lembrete Diário da Agenda', 'desc' => 'Receba um resumo dos atendimentos do dia toda manhã.'],
            ['key' => 'notifRelatorioSemanal', 'icon' => 'fa-chart-bar', 'color' => 'blue', 'title' => 'Relatório Semanal', 'desc' => 'Resumo financeiro e de atendimentos toda segunda-feira.'],
        ];
        @endphp

        @foreach($toggles as $t)
        <label class="flex items-center justify-between p-4 bg-[#0B0F19] rounded-2xl border border-gray-800 cursor-pointer hover:border-violet-800/50 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-{{ $t['color'] }}-900/30 border border-{{ $t['color'] }}-800/40 flex items-center justify-center text-{{ $t['color'] }}-400">
                    <i class="fa-solid {{ $t['icon'] }} text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-widest">{{ $t['title'] }}</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $t['desc'] }}</p>
                </div>
            </div>
            <button type="button" @click="{{ $t['key'] }} = !{{ $t['key'] }}"
                :class="{{ $t['key'] }} ? 'bg-violet-600' : 'bg-gray-700'"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-all shadow-inner">
                <span :class="{{ $t['key'] }} ? 'translate-x-6' : 'translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"></span>
            </button>
        </label>
        @endforeach
    </div>

    {{-- Seção: Regional --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl p-8 space-y-5 shadow-sm">
        <div class="flex items-center gap-3 pb-5 border-b border-gray-800/50">
            <div class="w-9 h-9 rounded-xl bg-violet-900/40 flex items-center justify-center border border-violet-800/40">
                <i class="fa-solid fa-earth-americas text-violet-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Configurações Regionais</h2>
                <p class="text-[10px] text-gray-500 font-medium">Ajustes de idioma, moeda e fuso horário.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Fuso Horário</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option selected>América/São_Paulo (UTC-3)</option>
                    <option>América/Manaus (UTC-4)</option>
                    <option>América/Belém (UTC-3)</option>
                    <option>UTC (Geral)</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Moeda Padrão</label>
                <select class="block w-full px-5 py-3.5 bg-[#0B0F19] border border-gray-800 rounded-2xl text-sm font-semibold text-gray-300 focus:ring-2 focus:ring-violet-500 outline-none appearance-none cursor-pointer transition-all">
                    <option selected>BRL — Real (R$)</option>
                    <option>USD — Dólar ($)</option>
                    <option>EUR — Euro (€)</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Seção: Manutenção --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl p-8 space-y-4 shadow-sm">
        <div class="flex items-center gap-3 pb-5 border-b border-gray-800/50">
            <div class="w-9 h-9 rounded-xl bg-rose-900/40 flex items-center justify-center border border-rose-800/40">
                <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold text-white uppercase tracking-widest">Zona de Atenção</h2>
                <p class="text-[10px] text-gray-500 font-medium">Ações que afetam a visibilidade e operação do estabelecimento.</p>
            </div>
        </div>

        <label class="flex items-center justify-between p-4 bg-rose-950/30 rounded-2xl border border-rose-900/40 cursor-pointer hover:border-rose-700/50 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-900/40 border border-rose-800/50 flex items-center justify-center text-rose-400">
                    <i class="fa-solid fa-gears text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-rose-300 uppercase tracking-widest">Modo de Manutenção</p>
                    <p class="text-[10px] text-rose-400/70 font-medium mt-0.5">Bloqueia novos agendamentos online temporariamente. Atendimentos em andamento não são afetados.</p>
                </div>
            </div>
            <button type="button" @click="modoManutencao = !modoManutencao"
                :class="modoManutencao ? 'bg-rose-600' : 'bg-gray-700'"
                class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-all shadow-inner">
                <span :class="modoManutencao ? 'translate-x-6' : 'translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"></span>
            </button>
        </label>
    </div>

    {{-- Botão Salvar --}}
    <div class="flex items-center gap-4 pb-6">
        <button @click="save()"
            class="btn-premium text-white px-10 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all shadow-xl shadow-violet-900/30 active:scale-95 flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            Salvar Configurações
        </button>
        <span x-show="saved" x-cloak x-transition
            class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest flex items-center gap-1.5">
            <i class="fa-solid fa-circle-check"></i> Configurações salvas!
        </span>
    </div>

</div>
@endsection
