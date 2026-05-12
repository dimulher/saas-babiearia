<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberSAAS Admin — @yield('title', 'Painel CEO')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .nav-item { @apply flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-white/10 hover:text-white transition-colors; }
        .nav-item.active { @apply bg-white/15 text-white font-semibold; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 antialiased" x-data="{}">

{{-- SIDEBAR --}}
<div class="fixed inset-y-0 left-0 w-56 bg-[#0A1B3D] flex flex-col z-50">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/10">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#E2C28A] rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-crown text-[#0A1B3D] text-sm"></i>
            </div>
            <div>
                <p class="text-white font-black text-sm tracking-tight">BARBERSAAS</p>
                <p class="text-blue-300 text-xs">Painel CEO</p>
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line w-4"></i> Dashboard
        </a>
        <a href="{{ route('admin.barbearias') }}" class="nav-item {{ request()->routeIs('admin.barbearias') ? 'active' : '' }}">
            <i class="fa-solid fa-store w-4"></i> Barbearias
        </a>
        <a href="{{ route('admin.usuarios') }}" class="nav-item {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}">
            <i class="fa-solid fa-users w-4"></i> Usuários
        </a>

        <div class="pt-4 border-t border-white/10 mt-4">
            <p class="text-xs text-blue-400 px-3 mb-2 uppercase tracking-wider">Plataforma</p>
            <a href="#" class="nav-item opacity-50 cursor-not-allowed">
                <i class="fa-solid fa-credit-card w-4"></i> Planos & Cobranças
            </a>
            <a href="#" class="nav-item opacity-50 cursor-not-allowed">
                <i class="fa-solid fa-chart-bar w-4"></i> Relatórios
            </a>
            <a href="#" class="nav-item opacity-50 cursor-not-allowed">
                <i class="fa-solid fa-gear w-4"></i> Configurações
            </a>
        </div>
    </nav>

    {{-- Footer --}}
    <div class="px-3 py-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-3 py-2 mb-2">
            <div class="w-8 h-8 bg-[#E2C28A] rounded-full flex items-center justify-center text-xs font-bold text-[#0A1B3D]">
                {{ Auth::guard('super_admin')->user()->initials }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate">{{ Auth::guard('super_admin')->user()->nome }}</p>
                <p class="text-blue-300 text-xs truncate">CEO</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="nav-item w-full text-red-300 hover:text-red-200 hover:bg-red-500/20">
                <i class="fa-solid fa-right-from-bracket w-4"></i> Sair
            </button>
        </form>
    </div>
</div>

{{-- CONTEÚDO --}}
<div class="ml-56 min-h-screen">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
            <p class="text-xs text-gray-400">@yield('subtitle', 'Visão geral da plataforma')</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                <i class="fa-solid fa-circle text-green-500 text-[8px] mr-1"></i> Sistema Online
            </span>
        </div>
    </header>

    {{-- Conteúdo --}}
    <main class="p-6">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif
        @yield('content')
    </main>
</div>

</body>
</html>
