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
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 3s ease-in-out infinite; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-[#0B0F19] min-h-screen flex items-center justify-center p-4">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-600/8 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-800/8 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 float shadow-xl shadow-green-900/40">
                <i class="fa-solid fa-user-tie text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Área do Funcionário</h1>
            <p class="text-gray-400 text-sm mt-1">Digite seu código de acesso para entrar</p>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-2xl p-8 relative">

            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest transition-colors mb-7">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('funcionario.login.post') }}" id="loginForm">
                @csrf

                <div class="mb-6">
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-3">Código de Acesso</label>
                    <input
                        type="text"
                        name="codigo"
                        id="codigo"
                        maxlength="6"
                        value="{{ old('codigo') }}"
                        placeholder="••••••"
                        autocomplete="off"
                        autofocus
                        class="w-full text-center text-3xl font-black tracking-[0.5em] bg-gray-900 border {{ $errors->has('codigo') ? 'border-red-500' : 'border-gray-700' }} rounded-xl px-4 py-5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-600 uppercase transition-all"
                        oninput="this.value = this.value.toUpperCase(); if(this.value.length === 6) document.getElementById('loginForm').submit();"
                    >
                    @error('codigo')
                        <p class="mt-2 text-sm text-red-400 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl transition-all active:scale-95 shadow-lg shadow-green-900/30">
                    Entrar
                </button>
            </form>
        </div>

    </div>

</body>
</html>
