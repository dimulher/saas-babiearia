@extends('layouts.funcionario')
@section('title', 'Minha Agenda')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ linkCopied: false }">

    {{-- BOAS-VINDAS --}}
    <div class="relative bg-gradient-to-br from-[#052e16] via-[#14532d] to-[#064e3b] border border-green-900/30 rounded-2xl overflow-hidden px-4 py-4 sm:px-6 sm:py-6">
        <div class="absolute top-0 right-0 w-72 h-72 bg-green-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-green-400 uppercase tracking-widest mb-0.5">Área do Funcionário</p>
                <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">
                    Olá, {{ explode(' ', $profissional->nome)[0] }}!
                </h2>
                <p class="text-xs text-gray-400 mt-0.5 font-medium">{{ now()->locale('pt_BR')->isoFormat('ddd, D [de] MMM [de] YYYY') }}</p>
            </div>

            {{-- Link de agendamento exclusivo --}}
            <div class="bg-black/20 border border-white/10 rounded-xl p-2.5 flex items-center gap-2 w-full sm:max-w-xs shrink-0">
                <i class="fa-solid fa-link text-green-400 text-xs shrink-0 ml-1"></i>
                <span class="text-xs text-green-100 font-mono flex-1 truncate">{{ $profissional->link_agendamento }}</span>
                <button
                    @click="navigator.clipboard.writeText('{{ $profissional->link_agendamento }}'); linkCopied = true; setTimeout(() => linkCopied = false, 2000)"
                    class="bg-white text-green-900 font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-1 shrink-0">
                    <i class="fa-solid fa-copy" x-show="!linkCopied"></i>
                    <i class="fa-solid fa-check text-green-600" x-show="linkCopied" x-cloak></i>
                    <span x-text="linkCopied ? 'Copiado!' : 'Copiar'"></span>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-green-400 font-medium flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- MÉTRICAS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-[#111827] border border-gray-800 hover:border-blue-800/50 rounded-2xl p-4 sm:p-5 flex items-center gap-3 sm:gap-4 transition-all group">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-900/30 text-blue-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-calendar-check text-base sm:text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Hoje</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosHoje }}</p>
                <p class="text-[10px] text-blue-500 font-bold mt-1">Agendamentos</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-emerald-800/50 rounded-2xl p-4 sm:p-5 flex items-center gap-3 sm:gap-4 transition-all group">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-900/30 text-emerald-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-calendar-week text-base sm:text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Semana</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosSemana }}</p>
                <p class="text-[10px] text-emerald-500 font-bold mt-1">Agendamentos</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-green-800/50 rounded-2xl p-4 sm:p-5 flex items-center gap-3 sm:gap-4 transition-all group">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-900/30 text-green-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-chart-line text-base sm:text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Mês</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosMes }}</p>
                <p class="text-[10px] text-green-500 font-bold mt-1">Agendamentos</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-amber-800/50 rounded-2xl p-4 sm:p-5 flex items-center gap-3 sm:gap-4 transition-all group">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-900/30 text-amber-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-hourglass-half text-base sm:text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pendentes</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosPendentes }}</p>
                <p class="text-[10px] text-amber-500 font-bold mt-1">A atender</p>
            </div>
        </div>
    </div>

    {{-- AGENDA + COLUNA DIREITA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LISTA DE AGENDAMENTOS --}}
        <div class="lg:col-span-2 bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <h3 class="font-bold text-white flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar-days text-green-500"></i>
                    Sua Agenda
                </h3>
                <form action="{{ route('funcionario.dashboard') }}" method="GET" class="flex bg-gray-900 p-1 rounded-xl border border-gray-800">
                    <button type="submit" name="filtro" value="hoje"
                        class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $filtro === 'hoje' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">
                        Hoje
                    </button>
                    <button type="submit" name="filtro" value="semana"
                        class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $filtro === 'semana' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">
                        Semana
                    </button>
                    <button type="submit" name="filtro" value="todos"
                        class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $filtro === 'todos' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">
                        Todos
                    </button>
                </form>
            </div>

            @if($agendamentos->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-700 border border-dashed border-gray-800 rounded-xl">
                    <i class="fa-regular fa-calendar-xmark text-3xl mb-3"></i>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Nenhum agendamento</p>
                    <p class="text-xs text-gray-600 mt-1">Você não tem horários marcados para este período.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($agendamentos as $ag)
                        <div class="flex items-center gap-3 px-3 py-3 bg-gray-900/60 border border-gray-800 hover:border-green-800/40 rounded-xl transition-all group"
                             x-data="{}">
                            <span class="text-xs font-black text-green-400 w-10 shrink-0">
                                {{ \Carbon\Carbon::parse($ag->data_inicio)->format('H:i') }}
                            </span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-white truncate">{{ $ag->cliente_nome ?? 'Cliente' }}</p>
                                <p class="text-[10px] text-gray-500 truncate flex items-center gap-1.5">
                                    <i class="fa-solid fa-scissors text-[8px]"></i>
                                    {{ $ag->servico?->nome ?? 'Serviço excluído' }}
                                    @if($ag->cliente_telefone)
                                        <span class="text-gray-700">·</span>
                                        <i class="fa-brands fa-whatsapp text-green-600 text-[8px]"></i>
                                        {{ $ag->cliente_telefone }}
                                    @endif
                                </p>
                            </div>

                            <div class="shrink-0 flex flex-col items-end gap-1.5">
                                <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full
                                    {{ $ag->status === 'pendente'   ? 'bg-amber-900/30 text-amber-400' : '' }}
                                    {{ $ag->status === 'confirmado' ? 'bg-blue-900/30 text-blue-400' : '' }}
                                    {{ $ag->status === 'concluido'  ? 'bg-emerald-900/30 text-emerald-400' : '' }}
                                    {{ $ag->status === 'cancelado'  ? 'bg-rose-900/30 text-rose-400' : '' }}
                                    {{ $ag->status === 'faltou'     ? 'bg-gray-800 text-gray-500' : '' }}">
                                    {{ ['pendente'=>'Pendente','confirmado'=>'Confirmado','concluido'=>'Concluído','cancelado'=>'Cancelado','faltou'=>'Faltou'][$ag->status] ?? $ag->status }}
                                </span>

                                @if(in_array($ag->status, ['pendente','confirmado']))
                                    <form action="{{ route('funcionario.agendamento.finalizar', $ag->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg bg-green-500/10 hover:bg-green-500 text-green-400 hover:text-white border border-green-500/20 hover:border-green-500 transition-all flex items-center gap-1">
                                            <i class="fa-solid fa-check-double text-[8px]"></i>Finalizar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- COLUNA DIREITA --}}
        <div class="space-y-5">

            {{-- Data e hora --}}
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5 text-center">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Agora</p>
                <p class="text-3xl font-black text-white" x-data="{ time: '' }" x-init="
                    const tick = () => { time = new Date().toLocaleTimeString('pt-BR', { hour:'2-digit', minute:'2-digit' }); };
                    tick(); setInterval(tick, 1000);
                " x-text="time"></p>
                <p class="text-xs text-gray-500 font-medium mt-1">{{ now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM') }}</p>
            </div>

            {{-- Seu link exclusivo --}}
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
                <h4 class="font-bold text-white mb-1 flex items-center gap-2 text-sm uppercase tracking-tight">
                    <i class="fa-solid fa-link text-green-500"></i> Seu Link Exclusivo
                </h4>
                <p class="text-[10px] text-gray-500 mb-4">Compartilhe com seus clientes para agendamento direto com você.</p>
                <div class="bg-gray-900 border border-gray-800 rounded-xl px-3 py-2.5 flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-user-tie text-green-500 text-xs shrink-0"></i>
                    <span class="text-xs text-gray-400 flex-1 truncate font-mono">{{ $profissional->link_agendamento }}</span>
                    <button @click="navigator.clipboard.writeText('{{ $profissional->link_agendamento }}'); linkCopied = true; setTimeout(() => linkCopied = false, 2000)"
                        class="text-green-500 hover:text-green-300 transition-colors shrink-0">
                        <i class="fa-regular fa-copy text-xs" x-show="!linkCopied"></i>
                        <i class="fa-solid fa-check text-xs" x-show="linkCopied" x-cloak></i>
                    </button>
                </div>
                <a href="{{ $profissional->link_agendamento }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-green-500/10 hover:bg-green-500 border border-green-500/30 hover:border-green-500 text-green-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    Ver página de agendamento
                </a>
            </div>

            {{-- Legenda de status --}}
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
                <h4 class="font-bold text-white mb-3 text-sm uppercase tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-gray-500"></i> Status
                </h4>
                <div class="space-y-2">
                    @foreach([
                        ['pendente',   'Pendente',   'bg-amber-900/30 text-amber-400 border-amber-800/50'],
                        ['confirmado', 'Confirmado', 'bg-blue-900/30 text-blue-400 border-blue-800/50'],
                        ['concluido',  'Concluído',  'bg-emerald-900/30 text-emerald-400 border-emerald-800/50'],
                        ['cancelado',  'Cancelado',  'bg-rose-900/30 text-rose-400 border-rose-800/50'],
                        ['faltou',     'Faltou',     'bg-gray-800 text-gray-500 border-gray-700'],
                    ] as [$key, $label, $cls])
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ $label }}</span>
                        <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border {{ $cls }}">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
