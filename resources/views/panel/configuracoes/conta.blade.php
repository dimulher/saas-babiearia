@extends('layouts.app')
@section('title', 'Minha Conta')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Minha Conta</h1>

    <!-- Avatar e info básica -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Informações Pessoais</h2>

        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-600">G</div>
            <div>
                <p class="text-sm font-medium text-gray-900">Foto de perfil</p>
                <p class="text-xs text-gray-500 mt-0.5">JPG ou PNG, máx. 2MB</p>
                <button class="mt-1.5 text-xs text-indigo-600 font-medium hover:text-indigo-800">Alterar foto</button>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome completo</label>
            <input type="text" value="Gabriel" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <input type="email" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
            <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="(00) 00000-0000">
        </div>
        <div class="pt-2">
            <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Salvar alterações
            </button>
        </div>
    </div>

    <!-- Segurança -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Segurança</h2>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Senha atual</label>
            <input type="password" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nova senha</label>
            <input type="password" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar nova senha</label>
            <input type="password" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="pt-2">
            <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Alterar senha
            </button>
        </div>
    </div>

    <!-- Zona de perigo -->
    <div class="bg-red-50 border border-red-100 rounded-2xl p-6 space-y-3">
        <h2 class="text-base font-semibold text-red-900">Zona de Perigo</h2>
        <p class="text-sm text-red-600">Estas ações são irreversíveis. Tenha certeza antes de prosseguir.</p>
        <button class="border border-red-300 text-red-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-100">
            Excluir minha conta
        </button>
    </div>

</div>
@endsection
