<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GlowSystem - @yield('title', 'Painel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017.umd.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .sidebar-item { transition: all 0.2s ease; border-radius: 10px; margin-bottom: 2px; }
        .sidebar-item:hover { background-color: rgba(22, 163, 74, 0.12); color: #86efac; }
        .sidebar-item.active { background-color: #16a34a; color: #ffffff; font-weight: 600; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); }
        .sidebar-submenu { border-left: 2px solid #1f2937; margin-left: 1.25rem; }
        [x-cloak] { display: none !important; }
        .btn-primary { background: #16a34a; transition: all 0.3s ease; }
        .btn-primary:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(22, 163, 74, 0.4); }
        .btn-premium { background: linear-gradient(135deg, #16a34a 0%, #059669 100%); transition: all 0.3s ease; }
        .btn-premium:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(22, 163, 74, 0.4); }
        .card-premium { border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); transition: all 0.3s ease; }
        .card-premium:hover { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); border-color: rgba(34, 197, 94, 0.3); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .turbo-progress-bar {
            height: 2px;
            background: linear-gradient(135deg, #16a34a 0%, #059669 100%);
            box-shadow: 0 0 8px rgba(22, 163, 74, 0.6);
        }

        #turbo-loading {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
        }
        #turbo-loading.active { display: block; }
        #turbo-loading-bar {
            position: absolute;
            top: 0; left: 0;
            height: 2px;
            width: 0%;
            background: linear-gradient(90deg, #16a34a, #059669);
            box-shadow: 0 0 10px rgba(22, 163, 74, 0.8);
            animation: loading-progress 1.5s ease-in-out infinite;
        }
        @keyframes loading-progress {
            0%   { width: 0%; opacity: 1; }
            50%  { width: 70%; opacity: 1; }
            100% { width: 90%; opacity: 0.8; }
        }

        /* Sidebar section labels */
        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #4b5563;
            padding: 12px 12px 4px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#0B0F19] text-gray-300" x-data="{
    mobileOpen: false,
    notifOpen: false,
    notifications: [],
    get unreadCount() { return this.notifications.filter(n => !n.read).length; },
    init() {
        this.fetchNotifications();
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

    <div id="turbo-loading"><div id="turbo-loading-bar"></div></div>

    {{-- ==================== HEADER ==================== --}}
    <header class="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[48] w-full bg-[#0B0F19] border-b border-gray-800/80 text-sm py-2.5 lg:ps-[220px]">
        <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">

            {{-- Logo mobile --}}
            <div class="me-5 lg:me-0 lg:hidden">
                <a href="/panel/dashboard" class="flex items-center gap-1">
                    <div class="w-7 h-7 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-bolt text-white text-xs"></i>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight ml-1">Glow<span class="text-green-500">System</span></span>
                </a>
            </div>

            <div class="w-full flex items-stretch justify-end">
                <div class="flex flex-row items-center justify-end gap-2">

                    {{-- Botão Novo Agendamento --}}
                    <a href="/panel/agendamentos"
                       class="hidden sm:flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-lg shadow-green-900/30">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Novo Agendamento
                    </a>

                    {{-- Sino de notificações --}}
                    <div class="relative" @click.outside="notifOpen = false">
                        <button @click="notifOpen = !notifOpen"
                            class="relative w-9 h-9 inline-flex justify-center items-center rounded-xl border border-gray-800 hover:border-green-700 hover:bg-green-900/20 text-gray-400 hover:text-green-400 transition-all focus:outline-none">
                            <i class="fa-solid fa-bell text-sm"></i>
                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-green-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow-lg animate-pulse"
                                x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </button>

                        <div x-show="notifOpen" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 top-full mt-2 z-50 w-[360px] bg-[#111827] shadow-2xl rounded-2xl border border-gray-800 overflow-hidden">

                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800/70">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-bell text-green-400 text-xs"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-widest">Notificações</span>
                                    <span x-show="unreadCount > 0" x-cloak
                                        class="px-2 py-0.5 bg-green-900/50 text-green-400 border border-green-800/50 rounded-full text-[9px] font-bold"
                                        x-text="unreadCount + ' nova' + (unreadCount > 1 ? 's' : '')"></span>
                                </div>
                                <button @click="markAllRead()" x-show="unreadCount > 0" x-cloak
                                    class="text-[10px] font-bold text-green-400 hover:text-green-300 uppercase tracking-wider transition-colors">
                                    Marcar todas
                                </button>
                            </div>

                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-800/50">
                                <template x-if="notifications.length === 0">
                                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                                        <i class="fa-regular fa-bell-slash text-3xl mb-3 text-gray-700"></i>
                                        <p class="text-xs font-bold uppercase tracking-widest">Nenhuma notificação</p>
                                    </div>
                                </template>

                                <template x-for="n in notifications" :key="n.id">
                                    <div @click="markRead(n.id)"
                                         :class="!n.read ? 'bg-green-950/20' : 'bg-transparent'"
                                         class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-800/50 cursor-pointer transition-all group relative">

                                        <span x-show="!n.read"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-green-500 rounded-full"></span>

                                        <div class="mt-0.5 shrink-0 w-8 h-8 rounded-xl flex items-center justify-center border"
                                             :class="{
                                                'bg-green-900/40 border-green-800/50 text-green-400': n.color === 'violet' || n.color === 'green',
                                                'bg-rose-900/40 border-rose-800/50 text-rose-400': n.color === 'rose',
                                                'bg-amber-900/40 border-amber-800/50 text-amber-400': n.color === 'amber',
                                                'bg-blue-900/40 border-blue-800/50 text-blue-400': n.color === 'blue',
                                             }">
                                            <i class="fa-solid text-xs" :class="n.icon"></i>
                                        </div>

                                        <div class="flex-1 min-w-0 pl-1">
                                            <p class="text-[11px] font-bold text-white uppercase tracking-wide leading-tight" x-text="n.title"></p>
                                            <p class="text-[11px] text-gray-400 mt-0.5 leading-snug line-clamp-2" x-text="n.body"></p>
                                            <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider mt-1.5" x-text="n.time"></p>
                                        </div>

                                        <button @click.stop="removeNotif(n.id)"
                                            class="shrink-0 opacity-0 group-hover:opacity-100 w-6 h-6 rounded-lg flex items-center justify-center text-gray-600 hover:text-rose-400 hover:bg-rose-900/20 transition-all">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div class="px-5 py-3 border-t border-gray-800/70 bg-[#0B0F19]">
                                <a href="/panel/configuracoes/sistema" class="text-[10px] font-bold text-gray-500 hover:text-green-400 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders text-xs"></i>
                                    Gerenciar preferências de notificação
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Avatar --}}
                    <div class="relative inline-flex" x-data="{ avatarOpen: false }">
                        <button @click="avatarOpen = !avatarOpen" class="size-[38px] inline-flex justify-center items-center text-sm font-semibold rounded-full border border-transparent focus:outline-none">
                            <div class="w-9 h-9 bg-green-900/50 text-green-400 rounded-full flex items-center justify-center text-xs font-bold select-none uppercase border border-green-800/50">
                                {{ auth()->user()->initials }}
                            </div>
                        </button>
                        <div x-show="avatarOpen" x-cloak @click.outside="avatarOpen = false"
                             class="absolute right-0 top-full mt-2 z-50 min-w-56 bg-[#111827] shadow-xl rounded-xl border border-gray-800">
                            <div class="py-3 px-5 bg-gray-800/50 rounded-t-xl">
                                <p class="text-xs text-gray-400">Logado como</p>
                                <p class="text-sm font-semibold text-white">{{ auth()->user()->email }}</p>
                                <p class="text-[10px] text-green-400 font-bold uppercase mt-0.5 tracking-wider">{{ auth()->user()->barbearia->nome }}</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="/panel/configuracoes/conta" class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                    <i class="fa-solid fa-user-gear w-4 text-gray-500"></i> Minha Conta
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
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

    {{-- ==================== BREADCRUMB MOBILE ==================== --}}
    <div class="sticky top-[49px] inset-x-0 z-20 bg-[#0B0F19] border-y border-gray-800/80 px-4 sm:px-6 lg:hidden">
        <div class="flex items-center py-2">
            <button @click="mobileOpen = true"
                class="px-4 py-1 flex justify-center items-center gap-x-2 border border-gray-700 text-gray-300 hover:text-white rounded-lg focus:outline-none text-sm font-medium">
                <i class="fa-solid fa-bars text-xs"></i>
                Menu
            </button>
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
    <div class="hidden lg:flex fixed inset-y-0 start-0 z-[60] w-[220px] bg-[#0d1117] border-e border-gray-800/80 flex-col">
        {{-- Logo --}}
        <div class="px-5 pt-6 pb-5 border-b border-gray-800/60">
            <a href="/panel/dashboard" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center shadow-lg shadow-green-900/40">
                    <i class="fa-solid fa-bolt text-white text-sm"></i>
                </div>
                <div>
                    <span class="text-white font-bold text-base tracking-tight">Glow<span class="text-green-400">System</span></span>
                </div>
            </a>
        </div>
        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-3 no-scrollbar" x-data="{}">
            @include('layouts.partials.nav-items')
        </nav>

        {{-- User footer --}}
        <div class="px-4 py-4 border-t border-gray-800/60">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-900/50 text-green-400 rounded-full flex items-center justify-center text-xs font-bold select-none uppercase border border-green-800/50 shrink-0">
                    {{ auth()->user()->initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->barbearia->nome }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-red-400 transition-colors" title="Sair">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MOBILE DRAWER ==================== --}}
    <div x-show="mobileOpen" x-cloak
         @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[59] bg-black/60 lg:hidden">
    </div>

    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 start-0 z-[60] w-[260px] bg-[#0d1117] border-e border-gray-800/80 flex flex-col lg:hidden shadow-2xl">

        <div class="px-5 pt-5 pb-4 border-b border-gray-800/60 flex items-center justify-between">
            <a href="/panel/dashboard" @click="mobileOpen = false" class="flex items-center gap-2">
                <div class="w-7 h-7 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-white text-xs"></i>
                </div>
                <span class="text-white font-bold text-base tracking-tight">Glow<span class="text-green-400">System</span></span>
            </a>
            <button @click="mobileOpen = false" class="p-1.5 hover:bg-gray-800 rounded-lg text-gray-500 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-3 no-scrollbar"
             x-data="{}"
             @click.capture="if($event.target.closest('a[href]')) mobileOpen = false">
            @include('layouts.partials.nav-items')
        </nav>
    </div>

    {{-- ==================== CONTEÚDO PRINCIPAL ==================== --}}
    <div class="w-full lg:ps-[220px]">
        <div class="p-4 sm:p-6 max-w-screen-2xl">
            @yield('content')
        </div>
    </div>

    <script>
        Turbo.setProgressBarDelay(0);

        const CACHE_KEY  = 'glowsystem_page_cache';
        const CACHE_TTL  = 5 * 60 * 1000;
        const loadingEl  = document.getElementById('turbo-loading');

        function cacheGet(url) {
            try {
                const raw = sessionStorage.getItem(CACHE_KEY + url);
                if (!raw) return null;
                const { html, ts } = JSON.parse(raw);
                if (Date.now() - ts > CACHE_TTL) { sessionStorage.removeItem(CACHE_KEY + url); return null; }
                return html;
            } catch { return null; }
        }
        function cacheSet(url, html) {
            try { sessionStorage.setItem(CACHE_KEY + url, JSON.stringify({ html, ts: Date.now() })); } catch {}
        }

        function getMain() { return document.querySelector('.w-full.lg\\:ps-\\[220px\\] > div'); }

        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || !href.startsWith('/panel') || href === location.pathname) return;
            const cached = cacheGet(href);
            if (cached) {
                const main = getMain();
                if (main) { main.innerHTML = cached; main.style.opacity = '0.6'; }
            }
            loadingEl.classList.add('active');
        }, true);

        document.addEventListener('turbo:before-render', (e) => { e.detail.newBody.style.opacity = '0'; });

        document.addEventListener('turbo:render', () => {
            loadingEl.classList.remove('active');
            document.body.style.opacity = '1';
            requestAnimationFrame(() => {
                const main = getMain();
                if (main) { main.style.opacity = '0'; main.style.transition = 'opacity 0.12s'; requestAnimationFrame(() => { main.style.opacity = '1'; }); }
            });
            if (window.Alpine) Alpine.initTree(document.body);
        });

        document.addEventListener('turbo:load', () => {
            loadingEl.classList.remove('active');
            document.body.style.opacity = '1';
            if (window.Alpine) Alpine.initTree(document.body);

            const main = getMain();
            if (main) cacheSet(location.pathname, main.innerHTML);

            setTimeout(() => {
                document.querySelectorAll('.sidebar-item[href]').forEach((link, i) => {
                    const href = link.getAttribute('href');
                    if (!href || cacheGet(href)) return;
                    setTimeout(() => {
                        fetch(href, { credentials: 'same-origin', headers: { 'Accept': 'text/html' } })
                            .then(r => r.text())
                            .then(html => {
                                const doc = new DOMParser().parseFromString(html, 'text/html');
                                const content = doc.querySelector('.w-full.lg\\:ps-\\[220px\\] > div');
                                if (content) cacheSet(href, content.innerHTML);
                            }).catch(() => {});
                    }, 600 + i * 200);
                });
            }, 1200);
        });

        document.addEventListener('mouseover', (e) => {
            const link = e.target.closest('a.sidebar-item[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || cacheGet(href)) return;
            fetch(href, { credentials: 'same-origin', headers: { 'Accept': 'text/html' } })
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const content = doc.querySelector('.w-full.lg\\:ps-\\[220px\\] > div');
                    if (content) cacheSet(href, content.innerHTML);
                }).catch(() => {});
        });
    </script>
</body>
</html>
