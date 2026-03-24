@extends('layouts.app')
@section('title', 'Configurações da Barbearia')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Configurações da Barbearia</h1>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">

        <!-- Logo -->
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-50">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">Logo da Barbearia</p>
                <p class="text-xs text-gray-500 mt-1">PNG ou JPG, máx. 2MB</p>
                <button class="mt-2 text-xs text-indigo-600 font-medium hover:text-indigo-800">Alterar logo</button>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Barbearia</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="(00) 00000-0000">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="(00) 00000-0000">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                    <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <input type="text" maxlength="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="SP">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="@suabarbearia">
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Salvar alterações
            </button>
        </div>
    </div>

</div>
@endsection
