@extends('layouts.app')
@section('title', 'Relatório de Clientes')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Relatório de Clientes</h1>
        <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
            <i class="fa-solid fa-download text-xs"></i> Exportar
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Março 2026</option>
        </select>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-xs text-gray-500 font-medium uppercase">Total de Clientes</p>
            <p class="text-3xl font-black text-gray-900 mt-1">0</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <p class="text-xs text-green-600 font-medium uppercase">Novos Clientes</p>
            <p class="text-3xl font-black text-green-700 mt-1">0</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-xs text-blue-600 font-medium uppercase">Clientes Recorrentes</p>
            <p class="text-3xl font-black text-blue-700 mt-1">0</p>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5">
            <p class="text-xs text-purple-600 font-medium uppercase">Taxa de Retorno</p>
            <p class="text-3xl font-black text-purple-700 mt-1">0%</p>
        </div>
    </div>

    <!-- Tabela top clientes -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Top Clientes do Período</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">#</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Cliente</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Visitas</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Última Visita</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Total Gasto</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Nenhum dado disponível para o período.</td></tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
