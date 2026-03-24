@extends('layouts.app')
@section('title', 'Gestão de Caixa')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Gestão de Caixa</h1>
        <div class="flex gap-2">
            <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
                <i class="fa-solid fa-arrow-up text-green-500 text-xs"></i> Entrada
            </button>
            <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
                <i class="fa-solid fa-arrow-down text-red-500 text-xs"></i> Saída
            </button>
            <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-800">
                <i class="fa-solid fa-box-archive text-xs"></i> Fechar Caixa
            </button>
        </div>
    </div>

    <!-- Status do caixa -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Saldo Atual</p>
            <p class="text-2xl font-black text-gray-900 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <p class="text-xs text-green-600 font-medium uppercase tracking-wide">Entradas Hoje</p>
            <p class="text-2xl font-black text-green-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <p class="text-xs text-red-600 font-medium uppercase tracking-wide">Saídas Hoje</p>
            <p class="text-2xl font-black text-red-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Lucro Hoje</p>
            <p class="text-2xl font-black text-blue-700 mt-1">R$ 0,00</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <input type="date" value="{{ now()->format('Y-m-d') }}" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os tipos</option>
            <option>Entrada</option>
            <option>Saída</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todas as formas</option>
            <option>Dinheiro</option>
            <option>Cartão de Crédito</option>
            <option>Cartão de Débito</option>
            <option>PIX</option>
        </select>
    </div>

    <!-- Tabela de lançamentos -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Data/Hora</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Descrição</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Forma</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Tipo</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Nenhum lançamento encontrado para hoje.</td></tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
