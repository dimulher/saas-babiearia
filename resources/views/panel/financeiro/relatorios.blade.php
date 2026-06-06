@extends('layouts.app')
@section('title', 'Relatórios Financeiros')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Relatórios Financeiros</h1>
        <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
            <i class="fa-solid fa-download text-xs"></i> Exportar PDF
        </button>
    </div>

    <!-- Seletor de período -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Janeiro 2026</option>
            <option>Fevereiro 2026</option>
            <option selected>Março 2026</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os profissionais</option>
        </select>
    </div>

    <!-- Resumo financeiro -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <p class="text-xs text-green-600 font-medium uppercase">Receita Bruta</p>
            <p class="text-3xl font-black text-green-700 mt-1">R$ 0,00</p>
            <p class="text-xs text-green-500 mt-2">0 agendamentos concluídos</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <p class="text-xs text-red-600 font-medium uppercase">Despesas</p>
            <p class="text-3xl font-black text-red-700 mt-1">R$ 0,00</p>
            <p class="text-xs text-red-500 mt-2">0 lançamentos</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-xs text-blue-600 font-medium uppercase">Lucro Líquido</p>
            <p class="text-3xl font-black text-blue-700 mt-1">R$ 0,00</p>
            <p class="text-xs text-blue-500 mt-2">Margem: 0%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Por serviço -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Receita por Serviço</h2>
            <div class="text-center py-8 text-gray-400 text-sm">Sem dados para o período</div>
        </div>
        <!-- Por profissional -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Receita por Profissional</h2>
            <div class="text-center py-8 text-gray-400 text-sm">Sem dados para o período</div>
        </div>
    </div>

    <!-- Por forma de pagamento -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Receita por Forma de Pagamento</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-2">Forma</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-2">Qtd.</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-2">Total</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-2">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['Dinheiro', 'PIX', 'Cartão de Débito', 'Cartão de Crédito'] as $forma)
                <tr class="border-b border-gray-800/50">
                    <td class="py-3 text-gray-700">{{ $forma }}</td>
                    <td class="py-3 text-right text-gray-500">0</td>
                    <td class="py-3 text-right text-gray-700 font-medium">R$ 0,00</td>
                    <td class="py-3 text-right text-gray-400">0%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
