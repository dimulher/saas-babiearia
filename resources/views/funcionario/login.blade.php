<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Funcionário — GlowSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glow { box-shadow: 0 0 40px rgba(139, 92, 246, 0.15); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 3s ease-in-out infinite; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center p-4">

    <!-- Background orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-800/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">

        <!-- Logo / Icon -->
        <div class="text-center mb-8 mt-6">
            <div class="w-20 h-20 bg-violet-600 rounded-[24px] flex items-center justify-center mx-auto mb-4 float glow">
                <i class="fa-solid fa-user-tie text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight uppercase">Área do Funcionário</h1>
            <p class="text-gray-400 text-sm mt-1">Digite seu código de acesso para entrar</p>
        </div>

        <!-- Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-[32px] p-8 glow relative">

            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest transition-colors mb-8">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-2xl text-green-400 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('funcionario.login.post') }}" id="loginForm">
                @csrf

                <div class="mb-6">
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-3">Código de Acesso</label>

                    <!-- Código com estilo de OTP -->
                    <input
                        type="text"
                        name="codigo"
                        id="codigo"
                        maxlength="6"
                        value="{{ old('codigo') }}"
                        placeholder="••••••"
                        autocomplete="off"
                        autofocus
                        class="w-full text-center text-3xl font-black tracking-[0.5em] bg-gray-800 border {{ $errors->has('codigo') ? 'border-red-500' : 'border-gray-700' }} rounded-2xl px-4 py-5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-violet-500 uppercase transition-all"
                        oninput="this.value = this.value.toUpperCase(); if(this.value.length === 6) document.getElementById('loginForm').submit();"
                    >

                    @error('codigo')
                        <p class="mt-2 text-sm text-red-400 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl transition-all active:scale-95">
                    Entrar
                </button>
            </form>
        </div>

    </div>

</body>
</html>
