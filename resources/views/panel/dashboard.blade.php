@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ══════════ BOAS-VINDAS ══════════ --}}
    <div class="relative bg-gradient-to-br from-[#052e16] via-[#14532d] to-[#064e3b] border border-green-900/30 rounded-2xl overflow-hidden px-6 py-6">
        <div class="absolute top-0 right-0 w-72 h-72 bg-green-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-green-400 uppercase tracking-widest mb-1">Painel de Controle</p>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Bem-vindo, {{ auth()->user()->name ?? 'Proprietário' }}!</h2>
                <p class="text-sm text-gray-400 mt-1 font-medium">{{ now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <a href="{{ url('/agendar/' . auth()->user()->barbearia->slug) }}" target="_blank"
               class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm px-5 py-3 rounded-xl font-bold uppercase tracking-wider transition-all shadow-lg shadow-green-900/30 w-full sm:w-auto justify-center">
                <i class="fa-solid fa-link text-xs"></i>
                Link do Estabelecimento
            </a>
        </div>
    </div>

    {{-- ══════════ GRID DE MÉTRICAS ══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="col-span-2 lg:col-span-1 bg-[#111827] border border-gray-800 hover:border-green-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-green-900/40 text-green-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-circle-dollar-to-slot text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Faturamento Mês</p>
                <p class="text-xl font-black text-white leading-none mt-1">R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</p>
                <p class="text-[10px] text-gray-500 font-medium mt-1">Hoje: R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-blue-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-blue-900/30 text-blue-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Hoje</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosHoje }}</p>
                <p class="text-[10px] text-blue-500 font-bold mt-1">Agendamentos</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-emerald-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-emerald-900/30 text-emerald-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-chart-line text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Mês</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosMes }}</p>
                <p class="text-[10px] text-emerald-500 font-bold mt-1">Agendamentos</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-amber-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-amber-900/30 text-amber-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-hourglass-half text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pendentes</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $agendamentosPendentes }}</p>
                <p class="text-[10px] text-amber-500 font-bold mt-1">Confirmados</p>
            </div>
        </div>
    </div>

    {{-- Linha 2 de métricas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#111827] border border-gray-800 hover:border-purple-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-purple-900/30 text-purple-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-receipt text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ticket Médio</p>
                <p class="text-xl font-black text-white leading-none mt-1">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</p>
                <p class="text-[10px] text-purple-500 font-bold mt-1">Por atendimento</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-teal-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-teal-900/30 text-teal-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Clientes</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $clientesAtivos }}</p>
                <p class="text-[10px] text-teal-500 font-bold mt-1">Cadastrados</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-green-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-green-900/30 text-green-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-user-group text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Equipe</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $profissionais }}</p>
                <p class="text-[10px] text-green-500 font-bold mt-1">Profissionais</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 hover:border-orange-800/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-12 h-12 bg-orange-900/30 text-orange-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-orange-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-magic-wand-sparkles text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Catálogo</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $servicos }}</p>
                <p class="text-[10px] text-orange-500 font-bold mt-1">Serviços ativos</p>
            </div>
        </div>
    </div>

    {{-- ══════════ AGENDA + COLUNA DIREITA ══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- AGENDAMENTOS HOJE --}}
        <div class="lg:col-span-2 bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar-check text-green-500"></i>
                    Agendamentos de Hoje
                </h3>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest bg-gray-900 px-3 py-1 rounded-full border border-gray-800">
                        {{ $selectedDate->locale('pt_BR')->isoFormat('ddd, D MMM') }}
                    </span>
                    <a href="/panel/agendamentos" class="text-[10px] font-black text-green-400 hover:text-green-300 uppercase tracking-widest transition-colors">
                        Ver tudo →
                    </a>
                </div>
            </div>

            @if($agendamentos->isEmpty())
            <div class="flex flex-col items-center justify-center py-10 text-gray-700 border border-dashed border-gray-800 rounded-xl">
                <i class="fa-regular fa-calendar-xmark text-3xl mb-2"></i>
                <p class="text-xs font-medium text-gray-500">Nenhum agendamento hoje</p>
            </div>
            @else
            <div class="space-y-2">
                @foreach($agendamentos as $ag)
                <div class="flex items-center gap-3 px-3 py-2.5 bg-gray-900/60 border border-gray-800 hover:border-green-800/40 rounded-xl transition-all group">
                    <span class="text-xs font-black text-green-400 w-10 shrink-0">{{ $ag->data_inicio->format('H:i') }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ $ag->nome_cliente }}</p>
                        <p class="text-[10px] text-gray-500 truncate">{{ $ag->servico?->nome }} · {{ $ag->profissional?->nome }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-xs font-black text-white">R$ {{ number_format($ag->preco, 2, ',', '.') }}</p>
                        <span class="text-[9px] font-bold uppercase
                            {{ $ag->status === 'concluido' ? 'text-emerald-400' : ($ag->status === 'cancelado' ? 'text-red-400' : 'text-green-400') }}">
                            {{ $ag->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="/panel/agendamentos" class="flex items-center justify-center gap-1.5 mt-4 py-2.5 text-[10px] font-black text-gray-500 hover:text-green-400 uppercase tracking-widest border border-gray-800 hover:border-green-800/40 rounded-xl transition-all">
                <i class="fa-solid fa-calendar-days text-[9px]"></i> Ver agenda completa
            </a>
            @endif
        </div>

        {{-- COLUNA DIREITA --}}
        <div class="space-y-5">

            {{-- Links de Agendamento --}}
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
                <h4 class="font-bold text-white mb-4 flex items-center gap-2 text-sm uppercase tracking-tight">
                    <i class="fa-solid fa-link text-green-500"></i> Links de Agendamento
                </h4>

                <div class="mb-3">
                    <p class="text-[10px] font-black text-green-400 uppercase tracking-widest mb-1.5">Estabelecimento (todos os profissionais)</p>
                    <div class="flex items-center gap-2 bg-gray-900 border border-green-800/30 rounded-xl px-3 py-2.5">
                        <i class="fa-solid fa-store text-green-500 text-xs shrink-0"></i>
                        <span class="text-xs text-gray-400 flex-1 truncate font-medium">{{ url('/agendar/' . auth()->user()->barbearia->slug) }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ url('/agendar/' . auth()->user()->barbearia->slug) }}').then(() => { this.innerHTML='<i class=\'fa-solid fa-check\'></i>'; setTimeout(()=>{ this.innerHTML='<i class=\'fa-regular fa-copy\'></i>'; }, 1500) })"
                            class="text-green-500 hover:text-green-300 transition-colors shrink-0">
                            <i class="fa-regular fa-copy text-xs"></i>
                        </button>
                    </div>
                </div>

                @if($profissionaisList->count())
                <div class="flex items-center gap-2 my-3">
                    <div class="flex-1 h-px bg-gray-800"></div>
                    <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">Por profissional</span>
                    <div class="flex-1 h-px bg-gray-800"></div>
                </div>

                <div class="space-y-2">
                    @foreach($profissionaisList as $p)
                    <div class="flex items-center gap-2 bg-gray-900 border border-gray-800 hover:border-gray-700 rounded-xl px-3 py-2.5 transition-colors">
                        <div class="w-6 h-6 bg-green-900/50 rounded-lg flex items-center justify-center text-[10px] font-black text-green-400 shrink-0">{{ $p->initials }}</div>
                        <span class="text-xs text-gray-400 flex-1 truncate font-medium">{{ $p->nome }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ url('/agendar/' . auth()->user()->barbearia->slug . '?funcionario=' . $p->id) }}').then(() => { this.innerHTML='<i class=\'fa-solid fa-check text-green-400\'></i>'; setTimeout(()=>{ this.innerHTML='<i class=\'fa-regular fa-copy\'></i>'; }, 1500) })"
                            class="text-gray-500 hover:text-green-400 transition-colors shrink-0">
                            <i class="fa-regular fa-copy text-xs"></i>
                        </button>
                        <a href="{{ url('/agendar/' . auth()->user()->barbearia->slug . '?funcionario=' . $p->id) }}" target="_blank"
                           class="text-gray-600 hover:text-green-400 transition-colors shrink-0">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Acesso Rápido --}}
            <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5">
                <h4 class="font-bold text-white mb-3 text-sm uppercase tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-amber-400"></i> Acesso Rápido
                </h4>
                <div class="space-y-1">
                    @foreach([
                        ['/panel/agendamentos',  'fa-calendar-days',        'text-blue-400',   'Agendamentos',  'Ver agenda completa'],
                        ['/panel/financeiro',    'fa-circle-dollar-to-slot','text-green-400',  'Financeiro',    'Relatório financeiro'],
                        ['/panel/profissionais', 'fa-user-group',           'text-purple-400', 'Equipe',        'Gerenciar profissionais'],
                        ['/panel/servicos',      'fa-magic-wand-sparkles',  'text-amber-400',  'Serviços',      'Catálogo de serviços'],
                    ] as [$url, $icon, $color, $label, $desc])
                    <a href="{{ $url }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-800 rounded-xl group transition-all">
                        <i class="fa-solid {{ $icon }} {{ $color }} text-sm w-4 text-center group-hover:scale-110 transition-transform"></i>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-200">{{ $label }}</p>
                            <p class="text-[10px] text-gray-500 font-medium">{{ $desc }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-700 text-[10px] group-hover:text-gray-400 group-hover:translate-x-0.5 transition-all"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Onboarding --}}
            <div class="bg-gradient-to-br from-green-900/80 to-emerald-950 border border-green-800/30 rounded-2xl p-5 shadow-xl shadow-green-900/20">
                <h4 class="font-bold text-white mb-4 text-sm flex items-center gap-2 uppercase tracking-widest">
                    <i class="fa-solid fa-rocket text-green-300"></i> Onboarding
                </h4>
                <ol class="space-y-3">
                    @php
                        $steps = [
                            ['label' => 'Dados do Estabelecimento', 'url' => route('panel.configuracoes.barbearia'),  'completed' => $barbearia?->email != null],
                            ['label' => 'Regras de Agendamento',    'url' => route('panel.configuracoes.agendamento'),'completed' => true],
                            ['label' => 'Cadastrar Serviços',       'url' => route('panel.servicos'),                 'completed' => $servicos > 0],
                            ['label' => 'Cadastrar Equipe',         'url' => route('panel.profissionais'),            'completed' => $profissionais > 0],
                        ];
                    @endphp
                    @foreach($steps as $i => $step)
                    <li class="flex items-center gap-3 text-xs">
                        @if($step['completed'])
                            <span class="w-5 h-5 bg-green-500/30 text-green-200 rounded-full flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </span>
                            <a href="{{ $step['url'] }}" class="text-green-300/60 line-through font-medium">{{ $step['label'] }}</a>
                        @else
                            <span class="w-5 h-5 bg-black/30 text-white rounded-full flex items-center justify-center font-black shrink-0 text-[10px]">{{ $i + 1 }}</span>
                            <a href="{{ $step['url'] }}" class="text-white hover:underline font-bold">{{ $step['label'] }}</a>
                        @endif
                    </li>
                    @endforeach
                </ol>
            </div>

        </div>
    </div>

</div>
@endsection
