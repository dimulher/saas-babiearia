<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberSAAS — Acesso CEO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Figtree', sans-serif; }</style>
</head>
<body class="min-h-screen bg-[#0A1B3D] flex items-center justify-center p-4">

<div class="w-full max-w-sm">

    <div class="text-center mb-8">
        <span class="text-4xl font-black tracking-tight text-white">BARBER</span><span class="text-4xl font-black text-[#E2C28A]">SAAS</span>
        <p class="mt-2 text-sm text-blue-300">Painel Administrativo — CEO</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8">

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A1B3D]"
                    placeholder="seu@email.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A1B3D]"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full bg-[#0A1B3D] hover:bg-[#122550] text-white font-semibold py-2.5 rounded-lg text-sm transition-colors mt-2">
                Entrar como CEO
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-gray-400">
            <a href="{{ route('login') }}" class="hover:text-gray-600">← Voltar ao login de clientes</a>
        </p>
    </div>
</div>

</body>
</html>
