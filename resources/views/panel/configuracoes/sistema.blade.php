@extends('layouts.app')
@section('title', 'Configurações do Sistema')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Configurações do Sistema</h1>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Idioma</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option selected>Português (Brasil)</option>
                <option>English</option>
                <option>Español</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fuso Horário</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option selected>América/São_Paulo (GMT-3)</option>
                <option>América/Manaus (GMT-4)</option>
                <option>América/Belém (GMT-3)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Moeda</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option selected>BRL — Real Brasileiro (R$)</option>
                <option>USD — Dólar Americano ($)</option>
                <option>EUR — Euro (€)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Formato de Data</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option selected>DD/MM/AAAA</option>
                <option>MM/DD/AAAA</option>
                <option>AAAA-MM-DD</option>
            </select>
        </div>

        <div class="space-y-3 pt-2 border-t border-gray-100">
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: true }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Notificações por e-mail</p>
                    <p class="text-xs text-gray-500">Receber alertas importantes por e-mail</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </label>
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: false }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Modo de manutenção</p>
                    <p class="text-xs text-gray-500">Desativa o agendamento online temporariamente</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-red-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </label>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Salvar configurações
            </button>
        </div>
    </div>

</div>
@endsection
