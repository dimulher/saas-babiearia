@extends('layouts.app')
@section('title', 'Extrato Financeiro')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Extrato Financeiro</h1>
        <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
            <i class="fa-solid fa-download text-xs"></i> Exportar
        </button>
    </div>

    <!-- Filtros de período -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
        <div class="flex gap-2">
            <button class="px-3 py-1.5 text-sm rounded-lg bg-indigo-600 text-white font-medium">Hoje</button>
            <button class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium">Semana</button>
            <button class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium">Mês</button>
            <button class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium">Personalizado</button>
        </div>
        <div class="flex items-center gap-2 ml-auto">
            <input type="date" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span class="text-gray-400 text-sm">até</span>
            <input type="date" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <p class="text-xs text-green-600 font-medium uppercase tracking-wide">Total Entradas</p>
            <p class="text-2xl font-black text-green-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <p class="text-xs text-red-600 font-medium uppercase tracking-wide">Total Saídas</p>
            <p class="text-2xl font-black text-red-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Saldo do Período</p>
            <p class="text-2xl font-black text-blue-700 mt-1">R$ 0,00</p>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Data</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Descrição</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Categoria</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Forma de Pagamento</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Valor</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">Nenhuma movimentação encontrada no período.</td></tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
