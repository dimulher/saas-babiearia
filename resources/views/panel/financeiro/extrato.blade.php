@extends('layouts.app')
@section('title', 'Extrato Financeiro')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Extrato Financeiro</h1>
            <p class="text-sm text-gray-400 font-medium">Movimentações detalhadas do período selecionado.</p>
        </div>
        <button class="flex items-center gap-2 bg-gray-800 border border-gray-700 text-gray-300 hover:text-white hover:border-gray-600 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
            <i class="fa-solid fa-download text-xs"></i> Exportar
        </button>
    </div>

    <!-- Filtros de período -->
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 flex flex-wrap gap-3 items-center">
        <div class="flex gap-1.5">
            <button class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-lg bg-green-500 text-white shadow-sm">Hoje</button>
            <button class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-600 transition-all">Semana</button>
            <button class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-600 transition-all">Mês</button>
            <button class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-600 transition-all">Personalizado</button>
        </div>
        <div class="flex items-center gap-2 ml-auto">
            <input type="date" class="bg-gray-900 border border-gray-700 text-gray-300 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-green-500/50 focus:border-green-500 transition-all">
            <span class="text-gray-600 text-xs font-bold">até</span>
            <input type="date" class="bg-gray-900 border border-gray-700 text-gray-300 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-green-500/50 focus:border-green-500 transition-all">
        </div>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-[#111827] border border-gray-800/50 border-l-4 border-l-emerald-500 rounded-2xl p-5">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Entradas</p>
            <p class="text-2xl font-black text-emerald-400 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-[#111827] border border-gray-800/50 border-l-4 border-l-rose-500 rounded-2xl p-5">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Saídas</p>
            <p class="text-2xl font-black text-rose-400 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-[#111827] border border-gray-800/50 border-l-4 border-l-blue-500 rounded-2xl p-5">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Saldo do Período</p>
            <p class="text-2xl font-black text-blue-400 mt-1">R$ 0,00</p>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/60 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-5 py-4">Data</th>
                        <th class="px-5 py-4">Descrição</th>
                        <th class="px-5 py-4">Categoria</th>
                        <th class="px-5 py-4">Forma de Pagamento</th>
                        <th class="px-5 py-4 text-right">Valor</th>
                        <th class="px-5 py-4 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-file-invoice-dollar text-2xl text-gray-600"></i>
                            </div>
                            <p class="text-sm font-bold text-white uppercase tracking-widest">Nenhuma movimentação</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold tracking-widest">Selecione um período para visualizar o extrato.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
