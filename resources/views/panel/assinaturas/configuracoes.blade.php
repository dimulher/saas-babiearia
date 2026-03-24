@extends('layouts.app')
@section('title', 'Configurações de Assinaturas')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Configurações de Assinaturas</h1>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo de comissão padrão</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option>Porcentagem sobre serviços</option>
                <option>Valor fixo por atendimento</option>
                <option>Misto (fixo + porcentagem)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Percentual de comissão padrão (%)</label>
            <input type="number" min="0" max="100" value="30" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Periodicidade de pagamento padrão</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option>Semanal</option>
                <option selected>Quinzenal</option>
                <option>Mensal</option>
            </select>
        </div>

        <div class="space-y-3 pt-2">
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: true }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Incluir produtos nas comissões</p>
                    <p class="text-xs text-gray-500">Comissão também sobre venda de produtos</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
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
