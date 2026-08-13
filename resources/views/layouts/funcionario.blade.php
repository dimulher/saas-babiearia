<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GlowSystem - @yield('title', 'Área do Funcionário')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap"></noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .sidebar-item { transition: all 0.2s ease; border-radius: 10px; margin-bottom: 2px; }
        .sidebar-item:hover { background-color: rgba(22,163,74,0.12); color: #86efac; }
        .sidebar-item.active { background-color: #16a34a; color: #fff; font-weight: 600; box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-[#0B0F19] text-gray-300">

    {{-- HEADER --}}
    <header class="sticky top-0 inset-x-0 flex z-[48] w-full bg-[#0B0F19] border-b border-gray-800/80 text-sm py-2.5 lg:ps-[220px]">
        <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">
            {{-- Logo mobile --}}
            <div class="me-5 lg:me-0 lg:hidden">
                <a href="{{ route('funcionario.dashboard') }}" class="flex items-center gap-1">
                    <div class="w-7 h-7 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-bolt text-white text-xs"></i>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight ml-1">Glow<span class="text-green-500">System</span></span>
                </a>
            </div>
            <div class="w-full flex items-stretch justify-end">
                <div class="flex flex-row items-center justify-end gap-2">
                    {{-- Badge área do funcionário --}}
                    <span class="hidden sm:flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-green-400 bg-green-900/30 border border-green-800/50 px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-user-tie text-[9px]"></i>
                        Área do Funcionário
                    </span>

                    {{-- Avatar + logout --}}
                    <div class="relative inline-flex" x-data="{ avatarOpen: false }">
                        <button @click="avatarOpen = !avatarOpen" class="size-[38px] inline-flex justify-center items-center rounded-full border border-transparent focus:outline-none">
                            @if($profissional->foto ?? false)
                                <img src="{{ $profissional->foto }}" class="w-9 h-9 rounded-full object-cover border border-green-800/50">
                            @else
                                <div class="w-9 h-9 bg-green-900/50 text-green-400 rounded-full flex items-center justify-center text-xs font-bold select-none uppercase border border-green-800/50">
                                    {{ $profissional->initials ?? substr(session('funcionario_nome','?'), 0, 2) }}
                                </div>
                            @endif
                        </button>
                        <div x-show="avatarOpen" x-cloak @click.outside="avatarOpen = false"
                             class="absolute right-0 top-full mt-2 z-50 min-w-52 bg-[#111827] shadow-xl rounded-xl border border-gray-800">
                            <div class="py-3 px-5 bg-gray-800/50 rounded-t-xl">
                                <p class="text-xs text-gray-400">Logado como</p>
                                <p class="text-sm font-bold text-white">{{ session('funcionario_nome') }}</p>
                                <p class="text-[10px] text-green-400 font-bold uppercase mt-0.5 tracking-wider">Funcionário</p>
                            </div>
                            <div class="p-1.5">
                                <form method="POST" action="{{ route('funcionario.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white text-left transition-colors">
                                        <i class="fa-solid fa-right-from-bracket w-4 text-gray-500"></i> Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    {{-- Título mobile --}}
    <div class="sticky top-[49px] inset-x-0 z-20 bg-[#0B0F19] border-b border-gray-800/60 px-4 lg:hidden">
        <div class="py-2">
            <p class="text-sm font-bold text-white tracking-tight">@yield('title', 'Área do Funcionário')</p>
        </div>
    </div>

    {{-- SIDEBAR DESKTOP --}}
    <div class="hidden lg:flex fixed inset-y-0 start-0 z-[60] w-[220px] bg-[#0d1117] border-e border-gray-800/80 flex-col">
        <div class="px-5 pt-6 pb-5 border-b border-gray-800/60">
            <a href="{{ route('funcionario.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center shadow-lg shadow-green-900/40">
                    <i class="fa-solid fa-bolt text-white text-sm"></i>
                </div>
                <span class="text-white font-bold text-base tracking-tight">Glow<span class="text-green-400">System</span></span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-3 no-scrollbar">
            <div class="px-3">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#4b5563;padding:12px 12px 4px;">Painel</div>
                <a href="{{ route('funcionario.dashboard') }}"
                   class="sidebar-item flex items-center gap-3 py-2.5 px-3 text-sm {{ request()->is('funcionario/dashboard') ? 'active' : 'text-gray-400' }}">
                    <i class="fa-solid fa-house text-sm w-4 text-center"></i>
                    <span>Visão Geral</span>
                </a>
            </div>
            <div class="px-3">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#4b5563;padding:12px 12px 4px;">Agendamentos</div>
                <a href="{{ route('funcionario.agendamentos') }}"
                   class="sidebar-item flex items-center gap-3 py-2.5 px-3 text-sm {{ request()->is('funcionario/agendamentos') ? 'active' : 'text-gray-400' }}">
                    <i class="fa-solid fa-calendar-days text-sm w-4 text-center"></i>
                    <span>Agendamentos</span>
                </a>
            </div>
            <div class="px-3">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#4b5563;padding:12px 12px 4px;">Meu Horário</div>
                <a href="{{ route('funcionario.horarios') }}"
                   class="sidebar-item flex items-center gap-3 py-2.5 px-3 text-sm {{ request()->is('funcionario/horarios') ? 'active' : 'text-gray-400' }}">
                    <i class="fa-solid fa-clock text-sm w-4 text-center"></i>
                    <span>Horários</span>
                </a>
                <a href="{{ route('funcionario.bloqueios') }}"
                   class="sidebar-item flex items-center gap-3 py-2.5 px-3 text-sm {{ request()->is('funcionario/bloquear-horarios') ? 'active' : 'text-gray-400' }}">
                    <i class="fa-solid fa-calendar-xmark text-sm w-4 text-center"></i>
                    <span>Horários Bloqueados</span>
                </a>
            </div>
            <div class="px-3">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#4b5563;padding:12px 12px 4px;">Configurações</div>
                <a href="{{ route('funcionario.configuracoes') }}"
                   class="sidebar-item flex items-center gap-3 py-2.5 px-3 text-sm {{ request()->is('funcionario/configuracoes') ? 'active' : 'text-gray-400' }}">
                    <i class="fa-solid fa-user-gear text-sm w-4 text-center"></i>
                    <span>Minha Conta</span>
                </a>
            </div>
        </nav>

        <div class="px-4 py-4 border-t border-gray-800/60">
            <div class="flex items-center gap-3">
                @if($profissional->foto ?? false)
                    <img src="{{ $profissional->foto }}" class="w-8 h-8 rounded-full object-cover border border-green-800/50 shrink-0">
                @else
                    <div class="w-8 h-8 bg-green-900/50 text-green-400 rounded-full flex items-center justify-center text-xs font-bold select-none uppercase border border-green-800/50 shrink-0">
                        {{ $profissional->initials ?? substr(session('funcionario_nome','?'), 0, 2) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ session('funcionario_nome') }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Funcionário</p>
                </div>
                <form method="POST" action="{{ route('funcionario.logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-red-400 transition-colors" title="Sair">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- BOTTOM NAV MOBILE --}}
    <nav class="fixed bottom-0 inset-x-0 z-[48] lg:hidden bg-[#0d1117] border-t border-gray-800/80" style="padding-bottom: env(safe-area-inset-bottom)">
        <div class="grid grid-cols-5 h-16">
            <a href="{{ route('funcionario.dashboard') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->is('funcionario/dashboard') ? 'text-green-400' : 'text-gray-500 hover:text-gray-300' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Início</span>
            </a>
            <a href="{{ route('funcionario.agendamentos') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->is('funcionario/agendamentos') ? 'text-green-400' : 'text-gray-500 hover:text-gray-300' }}">
                <i class="fa-solid fa-calendar-days text-base"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Agenda</span>
            </a>
            <a href="{{ route('funcionario.horarios') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->is('funcionario/horarios') ? 'text-green-400' : 'text-gray-500 hover:text-gray-300' }}">
                <i class="fa-solid fa-clock text-base"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Horários</span>
            </a>
            <a href="{{ route('funcionario.bloqueios') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->is('funcionario/bloquear-horarios') ? 'text-green-400' : 'text-gray-500 hover:text-gray-300' }}">
                <i class="fa-solid fa-calendar-xmark text-base"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Bloqueios</span>
            </a>
            <a href="{{ route('funcionario.configuracoes') }}"
               class="flex flex-col items-center justify-center gap-1 transition-colors {{ request()->is('funcionario/configuracoes') ? 'text-green-400' : 'text-gray-500 hover:text-gray-300' }}">
                <i class="fa-solid fa-gear text-base"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Config.</span>
            </a>
        </div>
    </nav>

    {{-- CONTEÚDO --}}
    <div class="w-full lg:ps-[220px]">
        <div class="p-4 sm:p-6 pb-24 lg:pb-6 max-w-screen-2xl mx-auto">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
