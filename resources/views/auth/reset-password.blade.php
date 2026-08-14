@extends('layouts.guest')
@section('title', 'Nova Senha')

@section('content')
<div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-10 glow-green relative z-10">

    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 float shadow-xl shadow-green-900/40">
            <i class="fa-solid fa-lock-open text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Nova <span class="text-green-400">Senha</span></h1>
        <p class="text-gray-400 text-sm mt-1">Escolha uma senha forte para sua conta</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-2">
            <label for="email" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">E-mail da Conta</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-600">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}" required autofocus
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 focus:bg-gray-900 transition-all outline-none @error('email') border-red-600/50 @enderror"
                    placeholder="seu@email.com">
            </div>
            @error('email')
                <p class="mt-1 text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nova Senha</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-600">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input type="password" id="password" name="password" required
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 focus:bg-gray-900 transition-all outline-none @error('password') border-red-600/50 @enderror"
                    placeholder="mínimo 8 caracteres">
            </div>
            @error('password')
                <p class="mt-1 text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Confirmar Senha</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-600">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm font-medium text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 focus:bg-gray-900 transition-all outline-none"
                    placeholder="repita a nova senha">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-green-500 hover:bg-green-600 text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-xl shadow-lg shadow-green-900/30 transition-all active:scale-95">
            Redefinir Senha
        </button>
    </form>
</div>
@endsection
