@extends('layouts.app')
@section('title', 'Relatório de Serviços')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Relatório de Serviços</h1>
        <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
            <i class="fa-solid fa-download text-xs"></i> Exportar
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Março 2026</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os profissionais</option>
        </select>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-xs text-gray-500 font-medium uppercase">Serviços Realizados</p>
            <p class="text-3xl font-black text-gray-900 mt-1">0</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-xs text-gray-500 font-medium uppercase">Receita Total</p>
            <p class="text-3xl font-black text-gray-900 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-xs text-gray-500 font-medium uppercase">Serviço Mais Vendido</p>
            <p class="text-lg font-bold text-gray-900 mt-1">—</p>
        </div>
    </div>

    <!-- Tabela por serviço -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Desempenho por Serviço</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Serviço</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Qtd.</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Receita</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Ticket Médio</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">% Total</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Nenhum dado disponível para o período.</td></tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
