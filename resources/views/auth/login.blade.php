@extends('layouts.guest')
@section('title', 'Entrar')

@section('content')
<div class="bg-gray-900 rounded-[32px] border border-gray-800 p-10 glow relative z-10">

    <a href="{{ route('login') }}" class="absolute top-8 left-8 inline-flex items-center gap-2 text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest transition-colors mb-6">
        <i class="fa-solid fa-arrow-left"></i> Voltar
    </a>

    <!-- Logo / Icon -->
    <div class="text-center mb-10 mt-6">
        <div class="w-20 h-20 bg-violet-600 rounded-[24px] flex items-center justify-center mx-auto mb-4 float glow">
            <i class="fa-solid fa-crown text-3xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tighter uppercase">GLOW<span class="text-violet-500">SYSTEM</span></h1>
        <p class="text-gray-400 text-sm mt-1">Acesso do Proprietário</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login.proprietario.post') }}" class="space-y-6">
        @csrf

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-600 text-[10px] font-black uppercase tracking-widest px-4 py-3 rounded-2xl">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="space-y-2">
            <label for="email" class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">E-mail de Acesso</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/50 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none @error('email') border-rose-500/50 @enderror"
                    placeholder="seu@email.com">
            </div>
            @error('email')
                <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Senha</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input type="password" id="password" name="password" required
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/50 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none @error('password') border-rose-500/50 @enderror"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between px-1">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg bg-gray-900 border-gray-800 text-violet-600 focus:ring-violet-500">
                <span class="text-[11px] text-gray-500 font-bold uppercase tracking-tight group-hover:text-gray-300 transition-colors">Lembrar acesso</span>
            </label>
            <a href="#" onclick="alert('Recuperação de senha em desenvolvimento. Fale com o suporte.')" class="text-[11px] text-violet-500 font-bold uppercase tracking-tight hover:text-violet-400 transition-colors">
                Recuperar senha
            </a>
        </div>

        <button type="submit"
            class="w-full bg-violet-600 hover:bg-violet-500 text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl shadow-violet-900/20 transition-all active:scale-95">
            Acessar Painel
        </button>
    </form>

    <div class="mt-10 pt-10 border-t border-gray-800/50 text-center">
        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight">
            Novo por aqui?
            <a href="{{ route('register') }}" class="text-violet-500 font-black ml-1 hover:text-violet-400 transition-colors">Criar conta grátis</a>
        </p>
    </div>
</div>
@endsection
