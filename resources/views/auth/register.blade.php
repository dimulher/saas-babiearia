@extends('layouts.guest')
@section('title', 'Criar Conta')

@section('content')
<div class="card-premium p-8 sm:p-10 border border-white/5 shadow-2xl relative overflow-hidden group">
    {{-- Glow effect --}}
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-violet-600/10 blur-[100px] rounded-full"></div>
    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-fuchsia-600/10 blur-[100px] rounded-full"></div>

    <div class="relative z-10 text-center mb-10">
        <h1 class="text-4xl font-black tracking-tighter text-white uppercase italic">
            GLOW<span class="text-violet-500">SYSTEM</span>
        </h1>
        <div class="flex items-center justify-center gap-2 mt-3">
            <span class="h-[1px] w-8 bg-gradient-to-r from-transparent to-violet-500/50"></span>
            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-violet-400/80">Premium Access</p>
            <span class="h-[1px] w-8 bg-gradient-to-l from-transparent to-violet-500/50"></span>
        </div>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-6 relative z-10">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Seu Nome</label>
                <div class="relative group">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                    <input type="text" name="nome" value="{{ old('nome') }}" required
                        class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                        placeholder="Como te chamamos?">
                </div>
                @error('nome') <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Estabelecimento</label>
                <div class="relative group">
                    <i class="fa-solid fa-store absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                    <input type="text" name="barbearia_nome" value="{{ old('barbearia_nome') }}" required
                        class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                        placeholder="Ex: Glow Studio">
                </div>
                @error('barbearia_nome') <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">E-mail de Acesso</label>
            <div class="relative group">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                    placeholder="seu@contato.com">
            </div>
            @error('email') <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">WhatsApp de Contato</label>
            <div class="relative group">
                <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                <input type="text" name="telefone" value="{{ old('telefone') }}"
                    x-mask="(99) 99999-9999"
                    class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                    placeholder="(00) 00000-0000">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Senha</label>
                <div class="relative group">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                    <input type="password" name="password" required
                        class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                        placeholder="••••••••">
                </div>
                @error('password') <p class="mt-1 text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] ml-1">Confirmar Senha</label>
                <div class="relative group">
                    <i class="fa-solid fa-shield absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-violet-500 transition-colors text-xs"></i>
                    <input type="password" name="password_confirmation" required
                        class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white placeholder:text-gray-600 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 focus:bg-white/10 transition-all outline-none"
                        placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full btn-premium text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl shadow-violet-900/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                Criar Minha Jornada
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>

    <div class="mt-10 pt-8 border-t border-white/5 text-center relative z-10">
        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">
            Já possui acesso?
            <a href="{{ route('login') }}" class="text-violet-400 font-black ml-1 hover:text-violet-300 transition-colors">Entrar na plataforma</a>
        </p>
    </div>
</div>
@endsection
