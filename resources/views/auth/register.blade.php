@extends('layouts.guest')
@section('title', 'Criar Conta')

@section('content')
<div class="bg-[#111827] rounded-2xl border border-gray-800 p-8 sm:p-10 glow-green relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-green-600/8 blur-[100px] rounded-full"></div>
    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-emerald-600/8 blur-[100px] rounded-full"></div>

    <div class="relative z-10 text-center mb-8">
        <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-green-900/40">
            <i class="fa-solid fa-bolt text-xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Glow<span class="text-green-400">System</span></h1>
        <p class="text-gray-400 text-sm mt-1">Crie sua conta gratuitamente</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-5 relative z-10">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Seu Nome</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                    <input type="text" name="nome" value="{{ old('nome') }}" required
                        class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                        placeholder="Como te chamamos?">
                </div>
                @error('nome') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Estabelecimento</label>
                <div class="relative">
                    <i class="fa-solid fa-store absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                    <input type="text" name="barbearia_nome" value="{{ old('barbearia_nome') }}" required
                        class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                        placeholder="Ex: Glow Studio">
                </div>
                @error('barbearia_nome') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">E-mail de Acesso</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                    placeholder="seu@contato.com">
            </div>
            @error('email') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">WhatsApp de Contato</label>
            <div class="relative">
                <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                <input type="text" name="telefone" value="{{ old('telefone') }}"
                    x-mask="(99) 99999-9999"
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                    placeholder="(00) 00000-0000">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Senha</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                    <input type="password" name="password" required
                        class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                        placeholder="••••••••">
                </div>
                @error('password') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Confirmar Senha</label>
                <div class="relative">
                    <i class="fa-solid fa-shield absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                    <input type="password" name="password_confirmation" required
                        class="block w-full pl-10 pr-4 py-3.5 bg-gray-900/60 border border-gray-700 rounded-xl text-sm text-white placeholder:text-gray-600 focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"
                        placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-xl shadow-lg shadow-green-900/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                Criar Minha Conta
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-800/60 text-center relative z-10">
        <p class="text-[11px] text-gray-500 font-medium">
            Já possui acesso?
            <a href="{{ route('login') }}" class="text-green-400 font-bold ml-1 hover:text-green-300 transition-colors">Entrar na plataforma</a>
        </p>
    </div>
</div>
@endsection
