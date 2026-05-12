@extends('layouts.guest')
@section('title', 'Escolha seu Acesso')

@section('content')
<div class="bg-gray-900 rounded-[32px] border border-gray-800 p-10 max-w-lg mx-auto glow relative z-10">

    <!-- Logo / Icon -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-violet-600 rounded-[24px] flex items-center justify-center mx-auto mb-4 float glow">
            <i class="fa-solid fa-layer-group text-3xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tighter uppercase">GLOW<span class="text-violet-500">SYSTEM</span></h1>
        <p class="text-gray-400 text-sm mt-1">Beleza & Estética Inteligente</p>
    </div>

    <div class="text-center mb-8">
        <h2 class="text-xl font-bold text-white tracking-tight">Como você deseja acessar?</h2>
        <p class="text-xs text-gray-400 mt-2 font-medium">Selecione o seu perfil para ser direcionado ao painel correto.</p>
    </div>

    <!-- Opções -->
    <div class="space-y-4">
        <!-- Proprietário -->
        <a href="{{ route('login.proprietario') }}" class="group block bg-gray-900/50 border border-gray-800 rounded-2xl p-6 hover:bg-violet-900/20 hover:border-violet-800 transition-all cursor-pointer">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-gray-800 text-gray-400 group-hover:bg-violet-600 group-hover:text-white rounded-2xl flex items-center justify-center transition-all shadow-sm">
                    <i class="fa-solid fa-crown text-xl"></i>
                </div>
                <div class="flex-1 text-left">
                    <h3 class="text-base font-bold text-white group-hover:text-violet-400 transition-colors">Sou Proprietário</h3>
                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-1">Acesso total via E-mail</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-violet-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>

        <!-- Funcionário -->
        <a href="{{ route('funcionario.login') }}" class="group block bg-gray-900/50 border border-gray-800 rounded-2xl p-6 hover:bg-emerald-900/20 hover:border-emerald-800 transition-all cursor-pointer">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-gray-800 text-gray-400 group-hover:bg-emerald-600 group-hover:text-white rounded-2xl flex items-center justify-center transition-all shadow-sm">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <div class="flex-1 text-left">
                    <h3 class="text-base font-bold text-white group-hover:text-emerald-400 transition-colors">Sou Integrante da Equipe</h3>
                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-1">Acesso restrito via Código</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
    </div>

</div>
@endsection
