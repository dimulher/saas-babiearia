<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Agenda — @yield('title', 'Painel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 antialiased">

{{-- HEADER MOBILE --}}
<header class="bg-[#0A1B3D] text-white px-4 py-3 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-[#E2C28A] rounded-full flex items-center justify-center text-[#0A1B3D] text-xs font-bold">
            {{ auth()->user()->initials }}
        </div>
        <div>
            <p class="text-sm font-semibold">{{ auth()->user()->nome }}</p>
            <p class="text-xs text-blue-300">{{ auth()->user()->barbearia->nome ?? 'Barbearia' }}</p>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-blue-300 hover:text-white text-sm">
            <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </form>
</header>

{{-- CONTEÚDO --}}
<main class="max-w-lg mx-auto p-4 pb-24">
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    @yield('content')
</main>

{{-- NAV BOTTOM (mobile style) --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex z-50">
    <a href="{{ route('funcionario.dashboard') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs gap-1 {{ request()->routeIs('funcionario.dashboard') || request()->routeIs('funcionario.agenda') ? 'text-[#0A1B3D] font-semibold' : 'text-gray-400' }}">
        <i class="fa-solid fa-calendar-days text-lg"></i>
        Agenda
    </a>
    <a href="{{ route('funcionario.comissoes') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs gap-1 {{ request()->routeIs('funcionario.comissoes') ? 'text-[#0A1B3D] font-semibold' : 'text-gray-400' }}">
        <i class="fa-solid fa-coins text-lg"></i>
        Comissões
    </a>
    <a href="{{ route('funcionario.perfil') }}"
       class="flex-1 flex flex-col items-center py-3 text-xs gap-1 {{ request()->routeIs('funcionario.perfil') ? 'text-[#0A1B3D] font-semibold' : 'text-gray-400' }}">
        <i class="fa-solid fa-user text-lg"></i>
        Perfil
    </a>
</nav>

</body>
</html>
