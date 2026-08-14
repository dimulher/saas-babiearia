@extends('layouts.guest')
@section('title', 'Entrar')

@section('content')
<div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-10 glow-green relative z-10">

    <a href="{{ route('login') }}" class="absolute top-5 sm:top-8 left-5 sm:left-8 inline-flex items-center gap-2 text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Voltar
    </a>

    <div class="text-center mb-10 mt-6">
        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 float shadow-xl shadow-green-900/40">
            <i class="fa-solid fa-bolt text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Glow<span class="text-green-400">System</span></h1>
        <p class="text-gray-400 text-sm mt-1">Acesso do Proprietário</p>
    </div>

    <form method="POST" action="{{ route('login.proprietario.post') }}" class="space-y-5" autocomplete="off">
        @csrf
        {{-- Campos isca para enganar o autofill do navegador --}}
        <input type="text"     style="display:none" tabindex="-1" aria-hidden="true">
        <input type="password" style="display:none" tabindex="-1" aria-hidden="true">

        @if(session('error'))
            <div class="bg-red-950/50 border border-red-800/50 text-red-400 text-[10px] font-bold uppercase tracking-widest px-4 py-3 rounded-xl">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="space-y-2">
            <label for="email" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">E-mail de Acesso</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-600">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="off" readonly
                    onfocus="this.removeAttribute('readonly')"
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 focus:bg-gray-900 transition-all outline-none @error('email') border-red-600/50 @enderror"
                    placeholder="seu@email.com">
            </div>
            @error('email')
                <p class="mt-1 text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Senha</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-600">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input type="password" id="password" name="password" required autocomplete="new-password" readonly
                    onfocus="this.removeAttribute('readonly')"
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 focus:bg-gray-900 transition-all outline-none @error('password') border-red-600/50 @enderror"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between px-1">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-green-500 focus:ring-green-500">
                <span class="text-[11px] text-gray-500 font-medium group-hover:text-gray-300 transition-colors">Lembrar acesso</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-[11px] text-green-500 font-bold hover:text-green-400 transition-colors">
                Recuperar senha
            </a>
        </div>

        <button type="submit"
            class="w-full bg-green-500 hover:bg-green-600 text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-xl shadow-lg shadow-green-900/30 transition-all active:scale-95">
            Acessar Painel
        </button>
    </form>

    <div class="mt-8 pt-8 border-t border-gray-800/60 text-center">
        <p class="text-[11px] text-gray-500 font-medium">
            Novo por aqui?
            <a href="{{ route('register') }}" class="text-green-400 font-bold ml-1 hover:text-green-300 transition-colors">Criar conta grátis</a>
        </p>
    </div>
</div>
@endsection
