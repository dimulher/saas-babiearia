@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 sm:space-y-6 max-w-7xl mx-auto">

    {{-- Boas-vindas --}}
    <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Bem-vindo, gabriel!</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <a href="/agendar/gabriel" target="_blank"
               class="flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-sm px-5 py-2.5 rounded-lg font-semibold uppercase tracking-wide w-full sm:w-auto">
                <i class="fa-solid fa-link text-xs"></i> Link de Agendamento
            </a>
        </div>

        {{-- 4 Cards: empilhados no mobile, grade no desktop --}}
        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-blue-50 rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-calendar-check text-white text-base"></i>
                </div>
                <div>
                    <p class="text-xs text-blue-600 font-medium">Agendamentos Hoje</p>
                    <p class="text-3xl font-bold text-blue-700 leading-none mt-1">0</p>
                </div>
            </div>
            <div class="bg-green-50 rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-users text-white text-base"></i>
                </div>
                <div>
                    <p class="text-xs text-green-600 font-medium">Clientes Ativos</p>
                    <p class="text-3xl font-bold text-green-700 leading-none mt-1">0</p>
                </div>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-group text-white text-base"></i>
                </div>
                <div>
                    <p class="text-xs text-purple-600 font-medium">Profissionais</p>
                    <p class="text-3xl font-bold text-purple-700 leading-none mt-1">0</p>
                </div>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-scissors text-white text-base"></i>
                </div>
                <div>
                    <p class="text-xs text-yellow-600 font-medium">Serviços</p>
                    <p class="text-3xl font-bold text-yellow-600 leading-none mt-1">0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Agenda + Coluna direita --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- AGENDA (coluna principal) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5" x-data="{ tab: 'proximos' }">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-calendar-days text-indigo-500"></i> Agenda
            </h3>

            {{-- Data atual --}}
            <p class="text-center text-xl font-semibold text-gray-800 mb-4">
                {{ now()->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>

            {{-- Navegação semanal --}}
            <div class="flex items-center gap-1 mb-4">
                <button class="p-2 hover:bg-gray-100 rounded-lg shrink-0">
                    <i class="fa-solid fa-chevron-left text-gray-400 text-xs"></i>
                </button>
                <div class="flex-1 grid grid-cols-5 sm:grid-cols-7 gap-1 overflow-hidden">
                    @php
                        $startOfWeek = now()->startOfWeek();
                        // Mobile: mostra 5 dias centrado no dia atual (2 antes, hoje, 2 depois)
                        $todayIndex = now()->dayOfWeek; // 0=dom
                        $mobileStart = now()->copy()->subDays(2);
                    @endphp

                    {{-- Mobile: 5 dias ao redor do hoje --}}
                    @for($i = 0; $i < 5; $i++)
                        @php $day = $mobileStart->copy()->addDays($i); @endphp
                        <button class="flex flex-col items-center py-2 rounded-xl text-xs font-medium sm:hidden {{ $day->isToday() ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-600 border border-gray-100' }}">
                            <span class="text-xs">{{ strtoupper(mb_substr($day->locale('pt_BR')->isoFormat('ddd'), 0, 3)) }}</span>
                            <span class="text-base font-bold mt-0.5">{{ $day->day }}</span>
                        </button>
                    @endfor

                    {{-- Desktop: 7 dias da semana --}}
                    @for($i = 0; $i < 7; $i++)
                        @php $day = $startOfWeek->copy()->addDays($i); @endphp
                        <button class="hidden sm:flex flex-col items-center py-2 rounded-xl text-xs font-medium {{ $day->isToday() ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-600' }}">
                            <span>{{ strtoupper(mb_substr($day->locale('pt_BR')->isoFormat('ddd'), 0, 3)) }}</span>
                            <span class="text-base font-bold mt-0.5">{{ $day->day }}</span>
                        </button>
                    @endfor
                </div>
                <button class="p-2 hover:bg-gray-100 rounded-lg shrink-0">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                </button>
            </div>

            {{-- Filtro profissional --}}
            <div class="mb-3">
                <select class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-600 bg-white">
                    <option>
                        <i class="fa-solid fa-user"></i> Todos profissionais
                    </option>
                </select>
            </div>

            {{-- Botão Novo Agendamento (full width no mobile, igual BarberBook) --}}
            <a href="/panel/agendamentos"
               class="flex items-center justify-center gap-2 w-full bg-gray-900 hover:bg-gray-800 text-white text-sm py-3 rounded-lg font-semibold uppercase tracking-wide mb-4">
                <i class="fa-solid fa-calendar-plus text-xs"></i> Novo Agendamento
            </a>

            {{-- Tabs Próximos / Anteriores --}}
            <div class="flex gap-1 border-b border-gray-200 mb-4">
                <button @click="tab='proximos'"
                    :class="tab==='proximos' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-2.5 px-3 text-sm flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-calendar-check text-xs"></i> Próximos
                </button>
                <button @click="tab='anteriores'"
                    :class="tab==='anteriores' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-2.5 px-3 text-sm flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-calendar-xmark text-xs"></i> Anteriores
                </button>
            </div>

            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <i class="fa-regular fa-calendar-xmark text-4xl mb-3 text-gray-300"></i>
                <p class="text-sm text-gray-500">Nenhum agendamento encontrado para esta data</p>
            </div>
        </div>

        {{-- COLUNA DIREITA --}}
        <div class="space-y-4">

            {{-- Link de Agendamento --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-link text-indigo-500"></i> Seu Link de Agendamento
                </h4>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5">
                    <span class="text-xs text-gray-500 flex-1 truncate">seudominio.com/gabriel</span>
                    <button class="text-xs text-indigo-600 font-semibold hover:text-indigo-700 flex items-center gap-1 shrink-0">
                        <i class="fa-regular fa-copy"></i> Copiar
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-2 leading-relaxed">Compartilhe este link com seus clientes para que possam agendar serviços diretamente.</p>
            </div>

            {{-- Ações Rápidas --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h4 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-400"></i> Ações Rápidas
                </h4>
                <div class="space-y-0.5">
                    @foreach([
                        ['/panel/agendamentos', 'bg-blue-100', 'fa-calendar-days', 'text-blue-600', 'Ver minha agenda', 'Gerencie seus agendamentos'],
                        ['/panel/profissionais', 'bg-purple-100', 'fa-user-group', 'text-purple-600', 'Profissionais', 'Gerencie sua equipe'],
                        ['/panel/servicos', 'bg-yellow-100', 'fa-scissors', 'text-yellow-600', 'Serviços', 'Gerencie seus serviços'],
                        ['/panel/clientes', 'bg-green-100', 'fa-users', 'text-green-600', 'Clientes', 'Gerencie seus clientes'],
                    ] as [$url, $bg, $icon, $color, $label, $desc])
                    <a href="{{ $url }}" class="flex items-center justify-between px-3 py-2.5 hover:bg-gray-50 rounded-lg group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 {{ $bg }} rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $icon }} {{ $color }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
                                <p class="text-xs text-gray-400">{{ $desc }}</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 text-xs group-hover:text-gray-400"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Guia de Início --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h4 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-yellow-400"></i> Guia de Início
                </h4>
                <ol class="space-y-2.5">
                    @foreach([
                        'Configure o nome da barbearia e os horários de funcionamento',
                        'Escolha o intervalo de tempo entre os agendamentos',
                        'Adicione os serviços oferecidos pela barbearia',
                        'Insira os dados dos profissionais da equipe',
                    ] as $i => $step)
                    <li class="flex items-start gap-2.5 text-xs text-gray-600">
                        <span class="w-5 h-5 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold shrink-0 mt-0.5 text-xs">{{ $i + 1 }}</span>
                        <a href="#" class="text-indigo-600 hover:underline leading-relaxed">{{ $step }}</a>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Avisos --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h4 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bell text-red-500"></i> Avisos Importantes
                </h4>
                <div class="flex flex-col items-center py-5 text-gray-400">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                    </div>
                    <p class="text-xs text-gray-400">Nenhum aviso no momento.</p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
