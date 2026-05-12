<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BarberSAAS') }} - @yield('title', 'Painel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .sidebar-item { transition: all 0.2s ease; border-radius: 12px; margin-bottom: 2px; }
        .sidebar-item:hover { background-color: rgba(124, 58, 237, 0.1); color: #a78bfa; }
        .sidebar-item.active { background-color: #7c3aed; color: #ffffff; font-weight: 600; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3); }
        .sidebar-submenu { border-left: 2px solid #1f2937; margin-left: 1.25rem; }
        [x-cloak] { display: none !important; }
        .btn-premium { background: linear-gradient(135deg, #7c3aed 0%, #c026d3 100%); transition: all 0.3s ease; }
        .btn-premium:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4); }
        .card-premium { border-radius: 32px; border: 1px solid rgba(255,255,255,0.05); background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); transition: all 0.3s ease; }
        .card-premium:hover { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); border-color: rgba(139, 92, 246, 0.3); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-[#0B0F19] text-gray-300" x-data="{
    mobileOpen: false,
    notifOpen: false,
    notifications: [],
    get unreadCount() { return this.notifications.filter(n => !n.read).length; },
    init() {
        this.fetchNotifications();
        // Opcional: atualizar a cada 1 minuto
        setInterval(() => this.fetchNotifications(), 60000);
    },
    fetchNotifications() {
        fetch('{{ route('panel.notificacoes.index') }}')
            .then(res => res.json())
            .then(data => this.notifications = data)
            .catch(err => console.error('Erro ao buscar notificações:', err));
    },
    markRead(id) { 
        const n = this.notifications.find(n => n.id === id); 
        if (n && !n.read) {
            n.read = true;
            fetch(`/panel/notificacoes/${id}/read`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
        }
    },
    markAllRead() { 
        if (this.unreadCount === 0) return;
        this.notifications.forEach(n => n.read = true);
        fetch('{{ route('panel.notificacoes.readAll') }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
    },
    removeNotif(id) { 
        this.notifications = this.notifications.filter(n => n.id !== id);
        fetch(`/panel/notificacoes/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
    }
}">

    {{-- ==================== HEADER ==================== --}}
    <header class="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[48] w-full bg-[#0B0F19] border-b border-gray-800 text-sm py-2.5 lg:ps-[208px]">
        <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">

            {{-- Logo (só mobile) --}}
            <div class="me-5 lg:me-0 lg:hidden">
                <a href="/panel/dashboard" class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none">
                    <span class="text-white font-extrabold text-xl tracking-tighter">GLOW</span><span class="text-violet-500 font-extrabold text-xl tracking-tighter">SYSTEM</span>
                </a>
            </div>

            <div class="w-full flex items-stretch justify-end">
                <div class="flex flex-row items-center justify-end gap-2">

                    {{-- ===== SINO DE NOTIFICAÇÕES ===== --}}
                    <div class="relative" @click.outside="notifOpen = false">
                        <button @click="notifOpen = !notifOpen; if(notifOpen) {}"
                            class="relative w-9 h-9 inline-flex justify-center items-center rounded-xl border border-gray-800 hover:border-violet-700 hover:bg-violet-900/20 text-gray-400 hover:text-violet-400 transition-all focus:outline-none">
                            <i class="fa-solid fa-bell text-sm"></i>
                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-violet-600 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow-lg shadow-violet-900/50 animate-pulse"
                                x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </button>

                        {{-- Dropdown de notificações --}}
                        <div x-show="notifOpen" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 top-full mt-2 z-50 w-[360px] bg-[#111827] shadow-2xl rounded-2xl border border-gray-800 overflow-hidden">

                            {{-- Header do dropdown --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800/70">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-bell text-violet-400 text-xs"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-widest">Notificações</span>
                                    <span x-show="unreadCount > 0" x-cloak
                                        class="px-2 py-0.5 bg-violet-900/50 text-violet-400 border border-violet-800/50 rounded-full text-[9px] font-bold"
                                        x-text="unreadCount + ' nova' + (unreadCount > 1 ? 's' : '')"></span>
                                </div>
                                <button @click="markAllRead()" x-show="unreadCount > 0" x-cloak
                                    class="text-[10px] font-bold text-violet-400 hover:text-violet-300 uppercase tracking-wider transition-colors">
                                    Marcar todas
                                </button>
                            </div>

                            {{-- Lista --}}
                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-800/50">
                                <template x-if="notifications.length === 0">
                                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                                        <i class="fa-regular fa-bell-slash text-3xl mb-3 text-gray-700"></i>
                                        <p class="text-xs font-bold uppercase tracking-widest">Nenhuma notificação</p>
                                    </div>
                                </template>

                                <template x-for="n in notifications" :key="n.id">
                                    <div @click="markRead(n.id)"
                                         :class="!n.read ? 'bg-violet-950/20' : 'bg-transparent'"
                                         class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-800/50 cursor-pointer transition-all group relative">

                                        {{-- Dot não lido --}}
                                        <span x-show="!n.read"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-violet-500 rounded-full"></span>

                                        {{-- Ícone --}}
                                        <div class="mt-0.5 shrink-0 w-8 h-8 rounded-xl flex items-center justify-center border"
                                             :class="{
                                                'bg-violet-900/40 border-violet-800/50 text-violet-400': n.color === 'violet',
                                                'bg-rose-900/40 border-rose-800/50 text-rose-400': n.color === 'rose',
                                                'bg-amber-900/40 border-amber-800/50 text-amber-400': n.color === 'amber',
                                                'bg-blue-900/40 border-blue-800/50 text-blue-400': n.color === 'blue',
                                             }">
                                            <i class="fa-solid text-xs" :class="n.icon"></i>
                                        </div>

                                        {{-- Conteúdo --}}
                                        <div class="flex-1 min-w-0 pl-1">
                                            <p class="text-[11px] font-bold text-white uppercase tracking-wide leading-tight" x-text="n.title"></p>
                                            <p class="text-[11px] text-gray-400 mt-0.5 leading-snug line-clamp-2" x-text="n.body"></p>
                                            <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider mt-1.5" x-text="n.time"></p>
                                        </div>

                                        {{-- Remover --}}
                                        <button @click.stop="removeNotif(n.id)"
                                            class="shrink-0 opacity-0 group-hover:opacity-100 w-6 h-6 rounded-lg flex items-center justify-center text-gray-600 hover:text-rose-400 hover:bg-rose-900/20 transition-all">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            {{-- Footer --}}
                            <div class="px-5 py-3 border-t border-gray-800/70 bg-[#0B0F19]">
                                <a href="/panel/configuracoes/sistema" class="text-[10px] font-bold text-gray-500 hover:text-violet-400 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders text-xs"></i>
                                    Gerenciar preferências de notificação
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ===== AVATAR ===== --}}
                    <div class="relative inline-flex" x-data="{ avatarOpen: false }">
                        <button @click="avatarOpen = !avatarOpen" class="size-[38px] inline-flex justify-center items-center text-sm font-semibold rounded-full border border-transparent focus:outline-none">
                            <div class="w-9 h-9 bg-indigo-900/50 text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold select-none uppercase">
                                {{ auth()->user()->initials }}
                            </div>
                        </button>
                        <div x-show="avatarOpen" x-cloak @click.outside="avatarOpen = false"
                             class="absolute right-0 top-full mt-2 z-50 min-w-56 bg-gray-900 shadow-xl rounded-lg border border-gray-800">
                            <div class="py-3 px-5 bg-gray-800/50 rounded-t-lg">
                                <p class="text-xs text-gray-400">Logado com</p>
                                <p class="text-sm font-medium text-white">{{ auth()->user()->email }}</p>
                                <p class="text-[10px] text-violet-400 font-bold uppercase mt-0.5 tracking-wider">{{ auth()->user()->barbearia->nome }}</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="/panel/configuracoes/conta" class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                                    <i class="fa-solid fa-user-gear w-4 text-gray-500"></i> Minha Conta
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white text-left">
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

    {{-- ==================== BREADCRUMB BAR MOBILE (igual ao BarberBook) ==================== --}}
    <div class="sticky top-[49px] inset-x-0 z-20 bg-[#0B0F19] border-y border-gray-800 px-4 sm:px-6 lg:hidden">
        <div class="flex items-center py-2">
            {{-- Botão Menu --}}
            <button @click="mobileOpen = true"
                class="px-4 py-1 flex justify-center items-center gap-x-2 border border-gray-700 text-gray-300 hover:text-white rounded-lg focus:outline-none text-sm font-medium">
                <i class="fa-solid fa-bars text-xs"></i>
                Menu
            </button>

            {{-- Breadcrumb --}}
            <ol class="ms-3 flex items-center whitespace-nowrap">
                <li class="flex items-center text-sm text-gray-500">
                    App
                    <svg class="shrink-0 mx-2 overflow-visible size-2.5 text-gray-600" fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke-linecap="round" stroke-width="2" stroke="currentColor"/>
                    </svg>
                </li>
                <li class="text-sm font-semibold text-white truncate">@yield('title', 'Painel')</li>
            </ol>
        </div>
    </div>

    {{-- ==================== SIDEBAR DESKTOP ==================== --}}
    <div class="hidden lg:block fixed inset-y-0 start-0 z-[60] w-[208px] bg-[#0B0F19] border-e border-gray-800">
        <div class="flex flex-col h-full">
            {{-- Logo --}}
            <div class="px-6 pt-7 pb-6 border-b border-gray-800/50">
                <a href="/panel/dashboard" class="flex items-center gap-1">
                    <span class="text-white font-extrabold text-2xl tracking-tighter">GLOW</span><span class="text-violet-500 font-extrabold text-2xl tracking-tighter">SYSTEM</span>
                </a>
            </div>
            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5 text-sm text-gray-600" x-data="{}">
                @include('layouts.partials.nav-items')
            </nav>
        </div>
    </div>

    {{-- ==================== MOBILE DRAWER ==================== --}}
    {{-- Backdrop --}}
    <div x-show="mobileOpen" x-cloak
         @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[59] bg-black/50 lg:hidden">
    </div>

    {{-- Drawer --}}
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 start-0 z-[60] w-[260px] bg-[#0B0F19] border-e border-gray-800 flex flex-col lg:hidden shadow-2xl">

        {{-- Drawer logo --}}
        <div class="px-5 pt-5 pb-4 border-b border-gray-800/50 flex items-center justify-between">
            <a href="/panel/dashboard" @click="mobileOpen = false" class="flex items-center gap-1">
                <span class="text-white font-extrabold text-xl tracking-tighter">GLOW</span><span class="text-violet-500 font-extrabold text-xl tracking-tighter">SYSTEM</span>
            </a>
            <button @click="mobileOpen = false" class="p-1.5 hover:bg-gray-800 rounded-lg text-gray-500 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Drawer nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5 text-sm text-gray-600"
             x-data="{}"
             @click.capture="if($event.target.closest('a[href]')) mobileOpen = false">
            @include('layouts.partials.nav-items')
        </nav>
    </div>

    {{-- ==================== CONTEÚDO PRINCIPAL ==================== --}}
    <div class="w-full lg:ps-[208px]">
        <div class="p-4 sm:p-6">
            @yield('content')
        </div>
    </div>

</body>
</html>
