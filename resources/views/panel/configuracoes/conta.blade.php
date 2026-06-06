@extends('layouts.app')
@section('title', 'Perfil de Usuário - GlowSystem')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Perfil de Usuário</h1>
        <p class="text-sm text-gray-400 font-medium">Gerencie suas credenciais de acesso e informações pessoais.</p>
    </div>

    <!-- Informações Pessoais -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="w-20 h-20 bg-green-900/30 rounded-[28px] flex items-center justify-center text-2xl font-bold text-green-500 border-2 border-green-800 italic">
                {{ substr(auth()->user()->name ?? 'G', 0, 1) }}
            </div>
            <div class="text-center sm:text-left">
                <h3 class="text-xs font-bold text-white uppercase tracking-widest">Identidade Visual</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Sua imagem será exibida nos logs e relatórios.</p>
                <button class="mt-3 text-[10px] font-bold text-green-500 uppercase tracking-widest hover:text-green-800 flex items-center gap-2">
                    <i class="fa-solid fa-camera-retro"></i> Mudar Avatar
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 pt-4 border-t border-gray-800/50">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome Completo</label>
                <input type="text" value="{{ auth()->user()->name ?? 'Usuário' }}"
                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">E-mail Corporativo</label>
                    <input type="email" value="{{ auth()->user()->email ?? '' }}"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Telefone Contato</label>
                    <input type="text" placeholder="(00) 00000-0000"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-800/50">
            <button class="w-full sm:w-auto bg-green-500 text-white px-10 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-900/20 active:scale-95 italic">
                Atualizar Cadastro <i class="fa-solid fa-user-check ml-2"></i>
            </button>
        </div>
    </div>

    <!-- Segurança -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 shadow-sm">
        <div>
            <h2 class="text-xs font-bold text-white uppercase tracking-widest">Segurança da Conta</h2>
            <p class="text-[10px] text-gray-400 font-medium mt-1">Recomendamos senhas fortes com números e símbolos.</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Senha Atual</label>
                <input type="password" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢"
                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nova Senha</label>
                    <input type="password" placeholder="Nova senha"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Confirmar Nova Senha</label>
                    <input type="password" placeholder="Repetir senha"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-800/50">
            <button class="w-full sm:w-auto bg-gray-900 text-white px-10 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-500 transition-all shadow-xl active:scale-95 italic">
                Redefinir Acesso <i class="fa-solid fa-lock ml-2"></i>
            </button>
        </div>
    </div>

    <!-- Zona de perigo -->
    <div class="bg-rose-50/50 border border-rose-100 rounded-[32px] p-8 space-y-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-900/50 rounded-xl flex items-center justify-center text-rose-500 shadow-sm border border-rose-100">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold text-rose-900 uppercase tracking-widest">Procedimento Crítico</h2>
                <p class="text-[10px] text-rose-600/70 font-bold uppercase tracking-tighter">A exclusão da conta é um processo irreversível.</p>
            </div>
        </div>
        <button class="w-full sm:w-auto px-6 py-3 bg-gray-900/50 border border-rose-200 text-rose-600 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all italic">
            Solicitar Exclusão de Conta
        </button>
    </div>

</div>
@endsection
