@extends('layouts.guest')
@section('title', 'Escolha seu Acesso')

@section('content')
<div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-10 max-w-lg mx-auto glow-green relative z-10">

    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 float shadow-xl shadow-green-900/40">
            <i class="fa-solid fa-bolt text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Glow<span class="text-green-400">System</span></h1>
        <p class="text-gray-400 text-sm mt-1">Beleza & Estética Inteligente</p>
    </div>

    <div class="text-center mb-8">
        <h2 class="text-lg font-bold text-white tracking-tight">Como você deseja acessar?</h2>
        <p class="text-xs text-gray-500 mt-2">Selecione o seu perfil para ser direcionado ao painel correto.</p>
    </div>

    <div class="space-y-4">
        <a href="{{ route('login.proprietario') }}" class="group block bg-gray-900/60 border border-gray-800 rounded-xl p-5 hover:bg-green-900/20 hover:border-green-700 transition-all cursor-pointer">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-800 text-gray-400 group-hover:bg-green-500 group-hover:text-white rounded-xl flex items-center justify-center transition-all">
                    <i class="fa-solid fa-crown text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <h3 class="text-sm font-bold text-white group-hover:text-green-400 transition-colors">Sou Proprietário</h3>
                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Acesso total via E-mail</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-green-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>

        <a href="{{ route('funcionario.login') }}" class="group block bg-gray-900/60 border border-gray-800 rounded-xl p-5 hover:bg-blue-900/20 hover:border-blue-700 transition-all cursor-pointer">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-800 text-gray-400 group-hover:bg-blue-500 group-hover:text-white rounded-xl flex items-center justify-center transition-all">
                    <i class="fa-solid fa-user-tie text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <h3 class="text-sm font-bold text-white group-hover:text-blue-400 transition-colors">Sou Integrante da Equipe</h3>
                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Acesso restrito via Código</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
    </div>

    <div class="mt-8 pt-6 border-t border-gray-800/60 text-center">
        <p class="text-[11px] text-gray-500 font-medium">
            Novo por aqui?
            <a href="{{ route('register') }}" class="text-green-400 font-bold ml-1 hover:text-green-300 transition-colors">Criar conta grátis</a>
        </p>
    </div>

</div>
@endsection
